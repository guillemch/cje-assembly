export default class API {
    constructor () {
      this.baseUrl = '/api/';
    }

    getAmendments () {
        return this._call('get', 'amendments/list');
    }

    getCurrentVote () {
        return this._call('get', 'vote/current');
    }

    _call (type, url, data) {
        return new Promise((resolve, reject) => {
            axios[type](this.baseUrl + url, data).then(response => {
                resolve(response.data);
            }).catch(error => {
                if (error.response.status === 500) {
                    reject({
                        'error': ['Error del sistema']
                    });
                } else if (error.response.data.hasOwnProperty('errors')){
                    reject(error.response.data.errors);
                } else {
                    reject(error.response.data);
                }
            });
        });
    }
}