import Form from 'belt/spot/js/events/form';
import config from 'belt/core/js/configs/store/local';
import params from 'belt/core/js/paramables/store';

export default {
    namespaced: true,
    modules: {
        config,
        params,
    },
    state: {
        form: new Form(),
    },
    mutations: {
        form: (state, form) => state.form = form,
    },
    actions: {
        construct: ({dispatch, commit}, options) => {
            dispatch('config/set', {morphType: 'events'});
            dispatch('params/set', {morphType: 'events', morphID: options.id});
        },
        load: ({commit, dispatch, state}, eventID) => {
            return new Promise((resolve, reject) => {
                state.form.show(eventID)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    })
            });
        },
        form: (context, form) => context.commit('form', form),
    },
    getters: {
        form: state => state.form,
    }
};