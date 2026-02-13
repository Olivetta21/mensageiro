<?php	
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods:POST");
	header("Access-Control-Allow-Headers: Content-Type, Authorization");

    //# DOTENV CONFIG
    define("ENVDIR",__DIR__);
    require ENVDIR . '/vendor/autoload.php';        
    Dotenv\Dotenv::createImmutable(ENVDIR)->load();
    //DOTENV CONFIG #
    
    session_start();

    function getClientIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        else return $_SERVER['REMOTE_ADDR'];
    }

	function fToJson($data) {
		$result = [];
		
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$result[$key] = mb_convert_encoding($value, 'UTF-8', 'auto');
			}
		}

		return json_encode($result);
	}

    function genTK(){
        return bin2hex(random_bytes(64));
    }


    function getDataBase() {
        
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];

        try {
            $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
            if ($pdo) return $pdo;
        } catch (Exception) {

        }

        echo fToJson(["error" => "database_error", "details"=>"failed to connect to database"]);
        exit;
    }

    function resetAccessToken() {
        setcookie(
            "access_token",
            "", time()-9999, "/"
        );            
        $_SESSION['access_token'] = null;
    }
    
    function validUserAndGetDB() {
        $db = getDataBase();

        if (!isset($_SESSION['access_token'])) {
            if (!isset($_COOKIE['access_token']) || empty($_COOKIE['access_token'])) {
                echo fToJson(["error" => "need_login", "details"=>"no token in session or cookie"]);
                exit;
            }
            $_SESSION['access_token'] = $_COOKIE['access_token'];
        }

        $access_token = $_SESSION['access_token'];

        if ($access_token) {
            $sql = "SELECT user_id, trunc(EXTRACT(EPOCH FROM (expires_at - NOW()))) AS expires_in FROM access_tokens WHERE token = :token AND expires_at > NOW()";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':token', $access_token, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    return ["db"=>$db, "user_id"=>$result['user_id'], "expires_in"=>$result['expires_in']];
                }

            } catch (Exception) {
                echo fToJson(["error" => "database_error", "details"=>"failed to fetch user"]);
                exit;
            }
            
            resetAccessToken();
        }

        // trap
        usleep(rand(1000, 10000) * 1000);
        http_response_code(500);
        exit;
    }

?>