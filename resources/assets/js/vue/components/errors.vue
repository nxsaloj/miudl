<template>
    <div class="alert alert-light" v-if="errorObj">
        <button type="button" class="close" @click.prevent="cerrar()"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        
        <!--Muestra el mensaje flash de si hubo un error o no en la operación-->
        <div>{{error.error}}</div>
        
        <template v-if="error.exception">
            <!--Muestra los detalles de la Excepción que capturó Try-->    
            <a href="#" @click.prevent="show = !show">Detalles</a>
            <div class="exception-dev card card-body foreground-grey" v-if="show">                
                    <span> {{(error.exception)}}</span>
            </div>
        </template>
        
        <!--Muestra los valores de validación de los campos del objeto-->            
        <template v-for="error in error.errors">
            <div><strong> {{error[0]}}</strong></div>
        </template>        
    </div>
</template>

<script type="text/babel">
import $ from "jquery";

export default {
  name: "error-component", 
  data(){
      return {
        show:false,
        errorObj : this.error
      }
  },
  watch:{
    proveedor (val) {
        if(val) window.scrollTo(0, 0);
    },
    error (val){
        this.errorObj = val;
        this.validar(val);
    }
  },
  props: {
      error:Object,
  },
  methods: {    
      cerrar(){
          this.errorObj = null;
      },
      validar(val)
      {          
        if(val)
        {
            const container = document.querySelector('.container-scroller');
            container.scrollTop = 0;              
        }
      }
  },
  computed: {
      
  }
};
</script>