<template>  
  <div id="app">    
    <b-navbar toggleable="lg" type="dark" class="nav-docente">
      <b-navbar-brand href="#">Grados y Títulos - UNSA</b-navbar-brand>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>          
          <b-nav-item :to="{ name: 'inicio' }">Expedientes</b-nav-item>
          <b-nav-item :to="{ name: 'reportes' }">Reportes</b-nav-item>
        </b-navbar-nav>        
      </b-collapse>
    </b-navbar>    
    <router-view/> <!--Mostrar contenido de la página-->
  </div>
</template>
<script>
export default {
    name: 'app', 
    data() {
      return {                               
        url: this.$root.API_URL,            
        codi_usuario: null,                                 
      }
    },
    created () {
      this.getCodiOper()            
    },    
    methods: {
      getCodiOper() {       
        this.axios.get(`${this.url}/codi_oper.php`)
          .then(response => {                  
            if (!response.data.error) {
              this.codi_usuario = response.data.codi_oper              
              this.getUsuario()
            }
            else {
              this.$root.mostrarNotificacion('Advertencia!', 'warning', 4000, 'error', response.data.message, 'bottom-right')
            }                  
          })
      },   
      getUsuario() {     
        let formData = new FormData()
        formData.append('codi_usuario', this.codi_usuario)                              

        this.axios.post(`${this.url}/Usuario/getIdUsuario`, formData)
        .then(response => {                
            if (!response.data.error) {
              let usuario = response.data.usuario        
              this.$store.dispatch('setUsuario', usuario)                           
            }
            else {                                        
              console.log(response.data.message)            
            }
        })
      },                 
    },    
}
</script>
<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale; 
  color: #2c3e50;
}
#nav {
  padding: 30px;
}
#nav a {
  font-weight: bold;
  color: #2c3e50;
}
#nav a.router-link-exact-active {
  color: #42b983;
}
.nav-docente {
  background-color: #4A9FCE;
  color: #fff;
}
.overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.6);
}
.counter {
    background-color:#f5f5f5;
    padding: 25px 15px;
    border-radius: 5px;
    min-height: 250px;
}
.count-title {
    font-size: 40px;
    font-weight: normal;
    margin-top: 10px;
    margin-bottom: 0;
    text-align: center;
}
.count-text {
    font-size: 15px;
    font-weight: normal;
    margin-top: 10px;
    margin-bottom: 0;
    text-align: center;
}    
.count-text-title {
    color: #4ad1e5;
    height: 50px;
}
</style>
