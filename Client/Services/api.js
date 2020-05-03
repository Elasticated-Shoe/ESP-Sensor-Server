export var api =  function(options) {
    return new Promise(function(resolve, reject) {
        var xhr = new XMLHttpRequest();
        
        xhr.open(options.method, options.url, true);
        xhr.onload = function() {
            if(this.status >= 200 && this.status < 300) {
                resolve(JSON.parse(xhr.response));
            }
            else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            }
        }
        xhr.onerror = function() {
            reject({
                status: this.status,
                statusText: xhr.statusText
            });
        }
        if(options.headers) {
            Object.keys(options.headers).forEach(function(key) {
                xhr.setRequestHeader(key, options.headers[key]);
            });
        }
        var params = options.params;
        if(params && typeof params === "object") {
            params = Object.keys(params).map(function(key) {
                return encodeURIComponent(key) + "=" + encodeURIComponent(params[key]);
            }).join("&");
        }
        xhr.send(params);
    });
}