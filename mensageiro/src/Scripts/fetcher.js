
export async function fetchJson(endereco, arrayValores) {
    //[{"h": "head", "b": "body"}]
    if (arrayValores && !Array.isArray(arrayValores)) {
        console.log("fetchJson", "o parametro: " + arrayValores + " não é um array!", "error");
        return [null];
    }

    try {
        const formData = new FormData();

        if (arrayValores != null && arrayValores.length > 0) {
            arrayValores.forEach(e => {
                formData.append(e.h, JSON.stringify(e.b));
            });
        }
        
        
        const dest_api = process.env.VUE_APP_BACKEND_ADDRESS;

        const response = await fetch(dest_api + endereco, {
            method: 'POST',
            body: formData
        });

        try {
            const result = await response.json();
            return result;
        }
        catch (error) {
            console.error("fetchjson-json", response);
        }
        
    } catch (error) {
        console.error("fetchjson", error);
    }

    return [null];
}