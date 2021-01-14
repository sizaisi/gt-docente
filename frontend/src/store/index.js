import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

import config from './../config'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    usuario: null,
    tramite: null,    
    procedimiento: null,    
    graduando: null,
    expediente: null,
    rutas: [],
    rutaVecinaActiva: false
  },
  mutations: {
    SET_USUARIO(state, usuario) {
      state.usuario = usuario
    },
    SET_TRAMITE(state, tramite) {
      state.tramite = tramite
    },    
    SET_PROCEDIMIENTO(state, procedimiento) {
      state.procedimiento = procedimiento
    }, 
    SET_GRADUANDO(state, graduando) {
      state.graduando = graduando
    },   
    SET_EXPEDIENTE(state, expediente) {
      state.expediente = expediente
    },    
    SET_RUTAS(state, rutas) {
      state.rutas = rutas
    },
    SET_RUTA_ACTIVA(state, rutaVecinaActiva) {
      state.rutaVecinaActiva = rutaVecinaActiva
    }
  },
  actions: {
    setUsuario({commit}, usuario) {
      commit('SET_USUARIO', usuario)
    },
    setTramite({commit}, tramite) {
      commit('SET_TRAMITE', tramite)
    },    
    setProcedimiento({commit}, procedimiento) {
      commit('SET_PROCEDIMIENTO', procedimiento)
    }, 
    setGraduando({commit}, graduando) {
      commit('SET_GRADUANDO', graduando)
    },
    setExpediente({commit}, expediente) {
      commit('SET_EXPEDIENTE', expediente)
    },
    getRutas({commit, state}) {
      let formData = new FormData()
      formData.append('idproc_origen', state.procedimiento.id)        

      axios.post(`${config.API_URL}/Ruta/getRutasByProc`, formData)
      .then(response => {                    
        if (!response.data.error) {     
            commit('SET_RUTAS', response.data.array_ruta)                      
        }
        else {              
            console.log(response.data.message)
        }
      })  
    },
    verificarRecursoRutasVecinas({ commit, state }, idruta) {           
      let formData = new FormData()
      formData.append('idexpediente', state.expediente.id)
      formData.append('idprocedimiento', state.procedimiento.id)
      formData.append('idusuario', state.usuario.id)
      formData.append('idruta', idruta)     

      axios.post(`${config.API_URL}/Recurso/verify`, formData)
        .then(response => {                                    
            if (!response.data.error) {     
              commit('SET_RUTA_ACTIVA', response.data.existeRecursoRutaVecinas)                         
            }
            else {                
              console.log(response.data.message)      
            }
        })      
    },
  },
  getters: {
    getUsuario: state => {
      return state.usuario;
    },
    getTramite: state => {
      return state.tramite;
    },
    getProcedimiento: state => {
      return state.procedimiento;
    },
    getGraduando: state => {
      return state.graduando;
    },    
    getExpediente: state => {
      return state.expediente;
    }, 
  },
  modules: {
  }
})
