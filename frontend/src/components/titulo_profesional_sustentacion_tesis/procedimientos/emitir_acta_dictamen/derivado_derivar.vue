<template>
    <b-card>
        <template v-if="!existeRecursoRutaVecinas">
            <b-card no-body>
                <b-tabs 
                    v-model="tabIndex" 
                    card        
                    active-nav-item-class="font-weight-bold text-uppercase text-danger"   
                    style="min-height: 250px"  
                    lazy                      
                >   
                    <b-tab title="1. Información de dictamen" title-item-class="disabledTab" :disabled="tabIndex2 < 0">
                        <b-row>
                            <b-col>
                                <b-form-group                                                                        
                                    label="Fecha de sesión de jurado:"
                                    label-for="input-1"                                    
                                >
                                    <b-form-input id="input-1" v-model="expediente.fecha_sesion_jurado" readonly class="text-center"></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col>
                                <b-form-group                                                                       
                                    label="Fecha de sustentación:"
                                    label-for="input-2"                                    
                                >
                                    <b-form-input id="input-2" v-model="expediente.fecha_sustentacion" readonly class="text-center"></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col>
                                <b-form-group                                                                      
                                    label="Hora de sustentación:"
                                    label-for="input-3"                                    
                                >
                                    <b-form-input id="input-3" v-model="expediente.hora_sustentacion" readonly class="text-center"></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <info_acta_dictamen 
                            :fecha_sesion_jurado="expediente.fecha_sesion_jurado"
                            :fecha_sustentacion="expediente.fecha_sustentacion"
                            :hora_sustentacion="expediente.hora_sustentacion"                           
                        /> 
                        <br>                        
                        <div v-if="errors.length" class="alert alert-danger" role="alert">
                            <ul><li v-for="(error, i) in errors" :key="i">{{ error }}</li></ul>
                        </div>                                  
                    </b-tab>  
                    <b-tab title="2. Generar documento" title-item-class="disabledTab" :disabled="tabIndex2 < 1">                        
                        <generacion_documento                                                                                     
                            :asesor="asesor"  
                            :jurados="array_jurado_confirmado"                          
                            nombre_archivo_pdf="acta_dictamen.php"
                            boton_nombre="Acta de dictamen"
                            ref="documentos"
                        />                      
                    </b-tab>  
                    <b-tab title="3. Añadir documento" title-item-class="disabledTab" :disabled="tabIndex2 < 2">
                        <documentos                                                                                                        
                            :ruta="ruta"                                                           
                            ref="documentos"
                            max_docs = "1"
                            nombre_asignado = "Acta de dictamen"
                        />
                        <div v-if="errors.length" class="alert alert-danger" role="alert">
                            <ul><li v-for="(error, i) in errors" :key="i">{{ error }}</li></ul>
                        </div>       
                    </b-tab>                   
                    <b-tab :title="'4. '+ruta.etiqueta.charAt(0).toUpperCase()+ruta.etiqueta.slice(1)+' expediente'" 
                        title-item-class="disabledTab" :disabled="tabIndex2 < 3">
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
                    <b-button @click="nextTab" :disabled="tabIndex == 3">Siguiente</b-button>
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
import info_acta_dictamen from '../../recursos/info_acta_dictamen.vue'
import generacion_documento from '../../recursos/generacion_documento.vue'
import documentos from '../../recursos/documentos.vue'
import movimiento_expediente from '../../recursos/movimiento_expediente.vue'

export default {
    name: 'derivado-derivar',
    props: {                       
        ruta: Object,
        movimiento: Object
    },
    components: {    
        info_acta_dictamen,
        generacion_documento,
        documentos,
        movimiento_expediente,           
    },
    data() {
        return {             
            url: this.$root.API_URL,                  
            tabIndex: 0,         
            tabIndex2: 0,              
            array_jurado_confirmado : [],            
            asesor : null,  //object    
            modalShow: false,                                     
            errors: [], 
        }
    },
    computed: {
        existeRecursoRutaVecinas() {
            return this.$store.state.rutaVecinaActiva
        },
        expediente() {
            return this.$store.state.expediente
        }
    },
    created() {                          
        this.$store.dispatch("verificarRecursoRutasVecinas", this.ruta.id);                   
        this.getJuradosConfirmados()
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
                pasar = this.validarTab0()
            }         
                            
            if (this.tabIndex == 1) {
                pasar = true
            }         
            
            if (this.tabIndex == 2) {
                pasar = this.validarTab2()
            }         

            if (pasar) {
                this.tabIndex2++
                this.$nextTick(function () {
                    this.tabIndex++        
                })  
            }              
        },   
        validarTab0() {        
            if (this.expediente.fecha_sesion_jurado == null ||
                this.expediente.fecha_sustentacion == null ||
                this.expediente.hora_sustentacion == null
            ) { 
                this.errors.push("Debe ingresar información de dictamen.")
            }                        

            if (!this.errors.length) {
                return true
            }      

            return false
        },
        validarTab2() {        
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
        getJuradosConfirmados() { // para obtener los jurados asignados por el usuario
            let formData = new FormData()
            formData.append('idexpediente', this.expediente.id)

            this.axios.post(`${this.url}/Persona/jurado_confirmado_expediente`, formData)
            .then(response => {            
                if (!response.data.error) {
                    this.array_jurado_confirmado = response.data.array_jurado_confirmado
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