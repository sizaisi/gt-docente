<template>
    <b-card>
        <template v-if="!existeRecursoRutaVecinas">
            <b-card no-body>
                <b-tabs 
                    v-model="tabIndex" 
                    card        
                    active-nav-item-class="font-weight-bold text-uppercase text-danger"   
                    style="min-height: 250px"                        
                >   
                    <b-tab title="1. Generar documento" title-item-class="disabledTab" :disabled="tabIndex2 < 0">
                        <generacion_documento                                                                    
                            :asesor="asesor"                                                    
                            nombre_archivo_pdf="informe_renuncia_asesoria.php"
                            boton_nombre="Informe de renuncia"
                            ref="documentos"
                        />                      
                    </b-tab>  
                    <b-tab title="2. Añadir documento" title-item-class="disabledTab" :disabled="tabIndex2 < 1">
                        <documentos                                                                             
                            :ruta="ruta"                                                           
                            ref="documentos"
                            max_docs = "1"
                            nombre_asignado = "Informe de renuncia de asesoria"
                        />
                        <div v-if="errors.length" class="alert alert-danger" role="alert">
                            <ul><li v-for="(error, i) in errors" :key="i">{{ error }}</li></ul>
                        </div>       
                    </b-tab>                   
                    <b-tab :title="'3. '+ruta.etiqueta.charAt(0).toUpperCase()+ruta.etiqueta.slice(1)+' expediente'" 
                        title-item-class="disabledTab" :disabled="tabIndex2 < 2">
                        <movimiento_expediente                            
                            :movimiento="movimiento"
                            :ruta="ruta"                                                            
                        />
                    </b-tab>
                </b-tabs>
            </b-card>        
            <div class="text-center">
                <b-button-group class="mt-3">
                    <b-button class="mr-1" @click="prevTab" :disabled="tabIndex == 0">Anterior</b-button>
                    <b-button @click="nextTab" :disabled="tabIndex == 2">Siguiente</b-button>
                </b-button-group>     
            </div> 
        </template>
        <template v-else>
            <div class="alert alert-danger" role="alert">
                <ul><li>Debe deshacer las acciones realizadas en otras opciones de este procedimiento</li></ul>
            </div>                                                                 
        </template>
    </b-card>       
</template>
<script>
import generacion_documento from '../../recursos/generacion_documento.vue'
import documentos from '../../recursos/documentos.vue'
import movimiento_expediente from '../../recursos/movimiento_expediente.vue'

export default {
    name: 'derivado-observar',
    props: {                        
        ruta: Object,
        movimiento: Object
    },
    components: {    
        generacion_documento,
        documentos,
        movimiento_expediente,           
    },
    data() {
        return {             
            url: this.$root.API_URL,                  
            expediente: this.$store.getters.getExpediente,
            tabIndex: 0,         
            tabIndex2: 0,             
            asesor : null,  //object                                         
            errors: [], 
        }
    },
    computed: {
        existeRecursoRutaVecinas() {
            return this.$store.state.rutaVecinaActiva
        }
    },
    created() {                          
        this.$store.dispatch("verificarRecursoRutasVecinas", this.ruta.id);                   
        this.getAsesor()                   
    },       
    methods: {            
        prevTab() {
            this.errors = [] 
            this.tabIndex2--       
            this.tabIndex--        
        },  
        nextTab() {      
            this.errors = [] 
            let pasar = false              
                            
            if (this.tabIndex == 0) {
                pasar = true
            }         
            
            if (this.tabIndex == 1) {
                pasar = this.validarTab1()
            }         

            if (pasar) {
                this.tabIndex2++
                this.$nextTick(function () {
                    this.tabIndex++        
                })  
            }              
        },   
        validarTab1() {        
            if (this.$refs.documentos.cantidadDocumentos() == 0) { //referencia al metodo del componente hijo
                this.errors.push("Debe registrar documentos para el expediente seleccionado.")
            }                        

            if (!this.errors.length) {
                return true
            }      

            return false
        },        
        getAsesor() {
            let formData = new FormData()
            formData.append('idexpediente', this.expediente.id)

            this.axios.post(`${this.url}/Persona/get_asesor_expediente`, formData)
            .then(response => {                             
                if (!response.data.error) {                
                    this.asesor = response.data.asesor
                }
                else {                
                    console.log(response.data.message)      
                }
            })    
        },                                                   
    },    
}
</script>
<style scoped>
    ul {
        margin-bottom: 0px;    
    }         
</style>
<style>
    .disabledTab{
        pointer-events: none;
    }          
</style>