import Vue from 'vue'
import axios from 'axios'
import paginador from '../../components/paginador.vue'
import toastel from '../../components/toastel.vue'
import vSelect from 'vue-select'
import Autocomplete from 'v-autocomplete'
import errorComponent from '../../components/errors'
import 'v-autocomplete/dist/v-autocomplete.css'

Vue.use(Autocomplete)
Vue.component('v-select', vSelect)

if(document.getElementsByClassName("vue-permisos").length > 0){
    if(document.getElementsByClassName("vue-permisos-create").length > 0)
    {
        window.onbeforeunload = confirmExit;
        function confirmExit() {
            return "Es posible que los cambios no se guarden. ¿Quiere salir de la página?";
        }
    }
    new Vue({
        el: ".vue-permisos",
        components: {
            paginador,
            toastel,
            errorComponent
            //Autocomplete
        },
        directives: {
            init: {
                bind: function(el, binding, vnode) {
                vnode.context[binding.arg] = binding.value;
                }
            }
        },
        data :
        {
            permiso:null,
            permisoid: (document.getElementById("permisoid"))? $('input[name=permisoid]').val():null,
            api_url:'/api/v1',
            url:'/administracion/secciones/',
            show:false,
            toggleCE:false,
            showextra:false,
            edit:false,
            first_edm:null,
            secciones:[],
            permisos:[],
            informes:[],
            secciones_permitidas:{"all":false,"active":[],"ignore":[]},
            usuarios:[],
            usuario:null,
            puestos:[],
            puesto:null,
            filtro:"",
            error:null,
            desde:{"Fecha":null,"Hora":null,"Tipo":0},
            hasta:{"Fecha":null,"Hora":null,"Tipo":0},
            delgar : false,
        },
        watch:{
            usuario (val) {
                if(!this.delegar) this.getSeccionesPermitidas();
            },
            puesto (val)
            {
                this.getSeccionesPermitidas();
            }
        },
        computed: {
          seccionesSidebar: function () {
            return this.secciones.filter(function (seccion) {
              return (seccion.Extra == null)
            })
          },
          notificaciones: function () {
            return this.secciones.filter(function (seccion) {
              return (seccion.Extra == 'notificacion')
            })
          },
          seccionesPermisos: function () {
            return this.secciones.filter(function (seccion) {
              return (seccion.Extra == 'permiso')
            })
          },
          informesPermisos: function () {
            return this.secciones.filter(function (seccion) {
              return (seccion.Extra == 'informes')
            })
          },
        },
        methods: {
            cambioTab(tipo){
                if(tipo == 'puesto') this.usuario = null;
                else if(tipo == 'usuario') this.puesto = null;
            },
            getLabel (item) {
                return item.Nombre;
            },
            limpiar(){
                this.filtro = null;
                this.filtrodocumento = null;
                this.getIngresos();
            },
            initDetalle(){
                this.detalle_tmp = {"CodigoRecurso":null,"Recurso":null,"Cantidad":null,"Precio":null,"Proyecto_id":null,"Cuenta_id":null};
            },
            addIgnore:function(id){
                if(this.secciones_permitidas.ignore.includes(id))
                {
                    this.secciones_permitidas.ignore.splice(this.secciones_permitidas.ignore.indexOf(id), 1);
                }
                else this.secciones_permitidas.ignore.push(id);
            },
            addActives:function(id){
                if(this.secciones_permitidas.active.includes(id))
                {
                    this.secciones_permitidas.active.splice(this.secciones_permitidas.active.indexOf(id), 1);
                }
                else this.secciones_permitidas.active.push(id);
            },
            addSelect:function($seccion, $todo=false){
                if($todo)
                {
                    this.secciones_permitidas.all = !this.secciones_permitidas.all;
                    if(this.secciones_permitidas.all)
                    {
                        this.secciones_permitidas.ignore = [];
                        this.secciones_permitidas.active = [];
                    }
                }
                else if($seccion)
                {
                    var id = $seccion.SeccionApp_id;

                    if(this.secciones_permitidas.all) this.addIgnore(id);
                    else this.addActives(id);
                }
            },
            //Métodos HTTP
            getSecciones: function() {
                var url = this.api_url+this.url;
                axios.get(url, {
                    params: this.getParametros()
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.secciones =  data.data;
                    }
                });
            },
            getSeccionesSelf: function() {
                var url = this.api_url+this.url+"self";
                axios.get(url, {
                    params: this.getParametros()
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.secciones =  data.data;
                    }
                });
            },
            getPermisosDe(){
                var permisos = {};
                if(this.usuario) permisos["Usuario_id"] = this.usuario.id;
                if(this.puesto) permisos["PuestoTrabajo_id"] = this.puesto.id;
                return permisos;
            },
            getSeccionesPermitidas: function() {
                this.secciones_permitidas={"all":false,"active":[],"ignore":[]};
                var url = this.api_url+this.url+'permitido';
                axios.get(url, {
                    params: this.getPermisosDe()
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.secciones_permitidas.active = data.data;
                    }
                });
            },
            getSeccionesDelegadas: function() {
                this.secciones_permitidas={"all":false,"active":[],"ignore":[]};
                var url = this.api_url+this.url+'delegado';
                axios.get(url, {
                    params: {"Usuario_id":$('input[name=delegado]').val()}
                  }).then(response => {
                    var data = response.data;
                    if(data.data)
                    {
                        this.secciones_permitidas.active = data.data;
                    }
                });
            },
            getUsuarios: function(filter, loading) {
                loading(true);
                var url = this.api_url+'/administracion/usuarios';
                axios.get(url, {
                    params: {"filtro":filter,"por_pagina":-1}
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        loading(false);
                        this.usuarios =  data.data;
                    }
                });
            },
            getPuestosTrabajo: function(filter, loading) {
                loading(true);
                var url = this.api_url+'/rrhh/puestos';
                axios.get(url, {
                    params: {"filtro":filter,"por_pagina":-1}
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        loading(false);
                        this.puestos =  data.data;
                    }
                });
            },
            guardarPermisos()
            {
                var url = this.api_url+this.url+'asociar';
                var params = this.getPermisosDe();
                params['secciones'] = this.secciones_permitidas;
                axios.post(url,
                    params
                ).then((response) => {
                    var data = response.data;
                    this.showToast("Información", data.meta.msj);
                },error => {
                    this.showToast("Alerta", "Ocurrión un error al tratar de guardar los permisos");
                });
            },
            guardarDelegacion()
            {
                var url = this.api_url+this.url+'delegar';
                var params = this.getPermisosDe();
                params['secciones'] = this.secciones_permitidas;
                params['desde'] = this.desde;
                params['hasta'] = this.hasta;

                axios.post(url,
                    params
                ).then((response) => {
                    var data = response.data;
                    this.showToast("Información", data.meta.msj);
                },error => {
                    this.showToast("Alerta", "Ocurrión un error al tratar de guardar los permisos");
                });
            },
            revocarPermisos()
            {
                var url = this.api_url+this.url+'undelegate';

                axios.post(url,
                    {"Delegacion_id":$('input[name=delegacion]').val()}
                ).then((response) => {
                    var data = response.data;
                    location.reload();
                },error => {
                    this.showToast("Alerta", "Ocurrión un error al tratar de guardar los permisos");
                });
            },
            notNull(detalle)
            {
                if(!detalle.Proyecto)
                {
                    return true;
                }
                return false;
            },
            getParametros:function(filter=null,por_pagina=false){
                var params = {};
                if(por_pagina) params['por_pagina'] = -1;


                if(filter) params['filtro'] = filter;
                else if(this.filtro) params['filtro'] = this.filtro;

                return params;
            },
            getParametrosSelect:function(filter=null,por_pagina=false){
                var params = {'orderby':  [null, null]};
                if(por_pagina) params['por_pagina'] = -1;
                if(filter) params['filtro'] = filter;
                return params;
            },
            showToast(title, text='El permiso se eliminó', icon=null,color='#33AA66'){
                $.toast({
                    heading: title,
                    text: text,
                    position: String("top-right"),
                    stack: false,
                    loader: false,
                    icon:icon,
                    showHideTransition: 'slide',
                    bgColor: color,
                });
            },
            getTipo:function(){
                var today = new Date();
                var hh = today.getHours();
                return (hh < 12)? 0:1;
            },
            getHora:function(pos=null){
                var today = new Date();
                var hh = today.getHours();
                var MM = today.getMinutes(); //January is 0!

                var hs = ""+hh;
                var ms = ""+MM;

                var hora = ((hs.length==1)? "0"+hh:hh)+":"+((ms.length==1)? "0"+MM:MM);
                return (pos)? ((pos == 1)? hh:MM):hora;
            },
            addDays:function(startDate,numberOfHours, numberOfDays=0, numberOfMonths=0, numberOfYears=0)
            {
                var returnDate = new Date(
                                        startDate.getFullYear()+numberOfYears,
                                        startDate.getMonth()+numberOfMonths,
                                        startDate.getDate()+numberOfDays,
                                        startDate.getHours()+numberOfHours,
                                        startDate.getMinutes(),
                                        startDate.getSeconds());
                return returnDate;
            },
            getFecha:function(_y=0,_m=0,_d=0){

                var today = this.addDays(new Date(), 0,_d,_m,_y)
                var dd = today.getDate();
                var mm = today.getMonth()+1; //January is 0!*/
                var yyyy = today.getFullYear();

                //if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'/'+mm+'/'+yyyy;
                if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+'-'+mm+'-'+dd;
                //console.log(today);
                return today;
            },
            cambiarHora(hora)
            {
                setTimeout(() => {
                    let hourObj = hora.Hora.split(":");
                    let hour = +hourObj[0];
                    let minutes = hourObj[1];

                    if(hour < 12 && hora.Tipo == 1)
                    {
                        let last = ""+(hour+12);
                        let hor =  ((last.length == 1)? "0"+last:last)+":"+minutes+":00";
                        hora.Hora = hor;
                        //console.log(hor);
                    }
                    else if(hour >= 12 && hora.Tipo == 0)
                    {
                        let last = ""+(hour-12);
                        let hor = ((last == 0)? "00":((last.length == 1)? "0"+last:last))+":"+minutes+":00";
                        hora.Hora = hor;
                        //console.log(last.length);
                    }

                    hora['custom'] =true;
                }, 300);
            },
        },
        created: function () {
            if(document.getElementsByClassName("vue-permisos-self").length <= 0)
            {
                this.getSecciones();
            }
            if(document.getElementsByClassName("vue-permisos-self").length > 0)
            {
                this.delegar = true;
                this.getSeccionesSelf();
                this.getSeccionesDelegadas();
            }
        }
    });

}
