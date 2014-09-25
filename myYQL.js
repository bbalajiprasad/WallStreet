function fetchYQLData( symbol , callback ) {
    var yql = 'http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20html%20where%20url%3D"http%3A%2F%2Ffinance.yahoo.com%2Fd%2F%3Fs%3D'+symbol+'%26f%3Dsb3l1b2k2hgvop"%3B&format=xml&callback=?';

    $.getJSON( yql, cbFunc );
    
    function cbFunc(data) {
        if ( data.results[0] ) {
            data = data.results[0].replace(/<script[^>]*>[\s\S]*?<\/script>/gi, '');    
            if ( typeof callback === 'function')
                callback(data);            
        }
        else throw new Error('Nothing returned from getJSON.');
    }
}