<template>
  <div>
    <b-button variant="warning" v-b-modal.info-dictamen>Editar</b-button> 
    <b-modal
      id="info-dictamen"
      ref="modal"
      title="INFORMACIÓN DE DICTAMEN"      
      @show="setData"
      @hidden="resetModal"
      @ok="actualizar"
      size="lg"
    >
        <template v-slot:modal-footer="{ ok, cancel }">
            <b-button size="sm" variant="danger" @click="cancel()">Cancelar</b-button>
            <b-button size="sm" variant="success" @click="ok()">Actualizar</b-button>
        </template>
      <form ref="form">
        <b-row>
            <b-col>
                <b-form-group label="Fecha sesión jurado:">
                    <b-form-datepicker v-model="info_dictamen.fecha_sesion_jurado" class="mb-2"></b-form-datepicker>
                </b-form-group>
            </b-col>
            <b-col>
                <b-form-group label="Fecha sustentación:">
                    <b-form-datepicker v-model="info_dictamen.fecha_sustentacion" class="mb-2"></b-form-datepicker>
                </b-form-group>
            </b-col>
        </b-row>  
        <b-row class="justify-content-lg-center">
            <b-col lg="3">
                <b-form-group label="Hora sustentación:">
                    <b-time v-model="info_dictamen.hora_sustentacion" locale="en"></b-time>
                </b-form-group>
            </b-col>
        </b-row>      
      </form>
    </b-modal>
  </div>
</template>

<script>
  export default {
    name: 'info-acta-dictamen',
    props: {        
        expediente: Object,
        fecha_sesion_jurado: String,
        fecha_sustentacion: String,
        hora_sustentacion: String,
    },
    data() {
      return {
        url: this.$root.API_URL,   
        info_dictamen: {
            fecha_sesion_jurado: '',
            fecha_sustentacion: '',
            hora_sustentacion: '',
        }                             
      }
    },     
    methods: {      
        setData() {
            this.info_dictamen.fecha_sesion_jurado = this.fecha_sesion_jurado
            this.info_dictamen.fecha_sustentacion = this.fecha_sustentacion
            this.info_dictamen.hora_sustentacion = this.hora_sustentacion
        },
        resetModal() {
            this.info_dictamen.fecha_sesion_jurado = '';
            this.info_dictamen.fecha_sustentacion = '';
            this.info_dictamen.hora_sustentacion = '';
        },
        actualizar() {                  
            let formData = new FormData()
            formData.append('idexpediente', this.expediente.id)
            formData.append('fecha_sesion_jurado', this.info_dictamen.fecha_sesion_jurado)          
            formData.append('fecha_sustentacion', this.info_dictamen.fecha_sustentacion)          
            formData.append('hora_sustentacion', this.info_dictamen.hora_sustentacion)          

            this.axios.post(`${this.url}/Expediente/upd_tp_st_acta_dictamen`, formData)
                .then(response => {        
                    //this.$log.debug('registrar info dictamen', response)                                
                    if (!response.data.error) {                                              
                        this.$root.mostrarNotificacion('Éxito!', 'success', 4000, 'done', response.data.message, 'bottom-right')                    
                        this.updateDataParent()
                    }
                    else {
                        this.$root.mostrarNotificacion('Error!', 'danger', 4000, 'error_outline', response.data.message, 'bottom-right')
                    }                
                    
                    this.$nextTick(() => {
                        this.$bvModal.hide('info-dictamen')
                    }) 
            })       
        },
        updateDataParent() {
            this.$emit('updateDataFromChild')
        }      
    }
  }
</script>