export default class API {
    constructor () {
      this.baseUrl = '/api/';
    }

    getUsers () {
        return this._call('get', 'credentials/list');
    }

    checkIn (user_id, undo) {
        return this._call('post', 'credentials/checkin', { user_id, undo });
    }

    getAmendments () {
        return this._call('get', 'amendments/list');
    }

    submitAmendment (data) {
        return this._call('post', 'amendments/new', data);
    }

    getCurrentVote () {
        return this._call('get', 'vote/current');
    }

    submitVote (data) {
        return this._call('post', 'vote/submit', data);
    }

    _call (type, url, data) {
        return new Promise((resolve, reject) => {
            axios[type](this.baseUrl + url, data).then(response => {
                resolve(response.data);
            }).catch(error => {
                if (error.response.status === 500) {
                    alert('La sesión ha caducado. Refresca el navegador y vuelve a iniciar sesión.');
                    location.reload();
                } else if (error.response.data.hasOwnProperty('errors')){
                    reject(error.response.data.errors);
                } else {
                    reject(error.response.data);
                }
            });
        });
    }
}