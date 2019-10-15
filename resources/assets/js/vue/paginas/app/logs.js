import Vue from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import paginador from '../../components/paginador.vue'
import toastel from '../../components/toastel.vue'
import vSelect from 'vue-select'
import VModal from 'vue-js-modal'
import Autocomplete from 'v-autocomplete'
import ItemTemplate from '../inventario/recursos/ItemTemplateResource.vue'
import errorComponent from '../../components/errors'
import 'v-autocomplete/dist/v-autocomplete.css'

Vue.use(VModal, { dynamic: true })
Vue.use(Autocomplete)
Vue.component('v-select', vSelect)

if(document.getElementsByClassName("vue-logs-admin").length > 0){
    /*if(document.getElementsByClassName("vue-logs-admin-create").length > 0)
    {
        window.onbeforeunload = confirmExit;
        function confirmExit() {
            return "Es posible que los cambios no se guarden. ¿Quiere salir de la página?";
        }
    }*/
    new Vue({
        el: ".vue-logs-admin",
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
            allow_save:true,
            saving:false,
            estadoRecurso: null,
            create_devolucion: false,
            current_page:1,
            solicitud:null,
            solicitudid: (document.getElementById("solicitudid"))? $('input[name=solicitudid]').val():null,
            api_url:'/api/v1',
            url:'/administracion/logs/',
            show:false,
            showextra:false,
            auto:true,
            prestamos:[],
            pagination:{
                "current_page":0,
                "last_page":0,
                "to":0,
                "per_page":0,
                "total":0               
            },
            orderby:{
                "field":'Nombre',
                "type":true
            },
            filtro:"",
            fecha:null,
            error:null,
            recursos:[],
            recursos_search:[],
            comentario:null,
            detalles_disponibilidad:[],
            proyectos:null,
            proyecto:null,
            recurso:null,
            nombre_recurso:null,
            detalle_tmp:{"CodigoRecurso":null,"Recurso":null,"Cantidad":1,"Precio":null,"Lote":null,"Comentario":null},
            detalles:[],
            recursomodal:null,
            template: ItemTemplate,
            logs:[]
        },
        computed: {
            findRecursos: function () {
                var detalles = this.detalles;
                return this.recursos.filter(function (recurso) {
                    let res = true;
                    if(detalles.length > 0)
                    {
                        detalles.forEach(function(detalle)
                        {
                            if(detalle.Recurso.CodigoEDM == recurso.CodigoEDM) res =false;
                        });
                    }
                    return res;
              })
            },
            prestamosComputed: function () {
                var detalles = this.detalles;
                return this.prestamos.filter(function (prestamo) {
                    let res = true;
                    if(detalles.length > 0)
                    {
                        detalles.forEach(function(detalle)
                        {
                            if(detalle.ReservacionHerramienta_id == prestamo.ReservacionHerramienta_id) res =false;
                        });
                    }
                    return res;
              })
            },
        },
        methods: {
            limpiar(){
                this.filtro = "";
                this.getLogs();
            },
            initDetalle(){
                this.lotes = null;
                this.detalle_tmp = {"CodigoRecurso":null,"Recurso":null,"Cantidad":1,"Precio":null,"Lote":null,"Comentario":null};
            },
            validarPagina(total, check)
            {
                if(!check)
                {
                    let actual = $('input[name=pagina]').val();
                    if(actual >= total)
                    {
                        $('input[name=pagina]').val(total);
                        $('input[name=completo]').prop('checked', true);
                    }
                    else{
                        $('input[name=completo]').prop('checked', false);
                    }
                }
                else{
                    let checked = $('input[name=completo]').prop('checked');
                    if(checked)
                    {
                        $('input[name=pagina]').val(total);
                    }
                    else $('input[name=pagina]').val("");
                }
            },
            //Métodos HTTP
            getRecursos: function(filter=null, loading=null) {
                if(loading) loading(true);
                var url = this.api_url+'/inventario/like/activos';
                this.nombre_recurso = (this.nombre_recurso)? this.nombre_recurso:$('input[name=recurso_nombre]').val();
                let filtro = (filter)? filter:this.nombre_recurso;
                
                axios.get(url, {
                    params: {"filtro":filtro,"por_pagina":-1}
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        if(loading) loading(false);
                        if(filter != null) this.recursos_search =  data.data;
                        else 
                        {                            
                            this.recursos = data.data;
                        }
                    }
                });
            }, 
            getLogs: function(filter=null, por_pagina=false, target=null) {
                var url = this.api_url+this.url;
                axios.get(url, {
                    params: this.getParametros(filter, por_pagina)
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {                        
                        this.logs = data.data;
                        this.pagination = data;
                        delete this.pagination.data;
                    }
                });
            }, 
            getActivos: function(filter=null, loading=null) {
                if(loading) loading(true);
                var url = this.api_url+'/inventario/like/activos/lista';
                this.nombre_recurso = (this.nombre_recurso)? this.nombre_recurso:$('input[name=recurso_nombre]').val();
                let filtro = (filter)? filter:this.nombre_recurso;
                
                axios.get(url, {
                    params: {"filtro":filtro,"por_pagina":-1}
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        if(loading) loading(false);
                        if(filter != null) this.recursos_search =  data.data;
                        else 
                        {                            
                            this.recursos = data.data;
                        }
                    }
                });
            }, 
            setRecurso:function(val){
                this.recurso = val;
                $('input[name=recurso_nombre]').val(val.Nombre);
                this.nombre_recurso =$('input[name=recurso_nombre]').val();
                this.getActivos();
            },
            setEstadoRecurso:function(detalle, $event)
            {
                if(!$event)
                {
                    if(detalle.estadorecurso) delete detalle.estadorecurso;//Vue.set(detalle, "detalleingreso", null);
                    else {                        
                        this.recursomodal = JSON.parse(JSON.stringify(detalle));
                        this.recursomodal.estadorecurso = {"Estado":null,"Comentario":null};
                        //console.log("Inicio ",this.recursomodal);
                        this.$modal.show('estado-recurso');
                    }           
                    this.$forceUpdate();
                }
                else{                         
                    this.recursomodal = JSON.parse(JSON.stringify(detalle));
                    this.$modal.show('estado-recurso');
                }
            }, 
            getIndex:function(id)
            {
                var index = null;
                for(var i = 0; i < this.detalles.length; i++)
                {
                    if(this.detalles[i]['ReservacionHerramienta_id'] == id)
                    {
                        index =  i+1;
                        break;
                    }
                }
                return index;
            },
            guardarEstadoRecurso:function(){
                
                let index = this.getIndex(this.recursomodal.ReservacionHerramienta_id);
                if(!index) return 0;
                if(this.recursomodal.estadorecurso.Estado)  this.detalles[index-1]['estadorecurso'] = this.recursomodal.estadorecurso;
                this.recursomodal = null;
                this.$forceUpdate();
                this.$modal.hide('estado-recurso');
            },
            searchRecursoEnter:function(val){
                this.nombre_recurso = val;
                //console.log(this.nombre_recurso);
                this.getActivos();
            },
            setRecursoNombre:function(val){
                if(this.recurso) this.recurso.Nombre = val;
                this.nombre_recurso = val;
            },
            getObjetoDevolucion:function(){
                var devolucion = {"Detalles":this.detalles};                
                return devolucion;
            },
            registrarDevolucion(){
                if(this.allow_save)
                {
                    this.saving = true;
                    this.allow_save=false;
                    var url = this.api_url+this.url;
                    axios.post(url, this.getObjetoDevolucion()).then(response => {
                        if(response.data)
                        {
                            var urli = response.data.url;
                            window.onbeforeunload = null;
                            window.location = urli.replace("api/v1/","");
                        }
                        this.saving = false;
                    })  
                    .catch(error => {
                        this.saving = false;
                        this.allow_save = true;
                        this.error = error.response.data;
                        this.showToast("Alerta", "Por favor verifique los datos ingresados","error","#ea2c54");
                    });
                }
            },
            notNull(detalle)
            {
                if(!detalle.Proyecto)
                {                    
                    return true;
                }
                return false;
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
            getFecha:function(_y=0,_m=0,_d=0){
                var today = new Date();
                var dd = today.getDate()+_d;
                var mm = today.getMonth()+1+_m; //January is 0!

                var yyyy = today.getFullYear()+_y;
                //if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'/'+mm+'/'+yyyy;
                if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+'-'+mm+'-'+dd;
                //console.log(today);
                return today;
            },
            getParametros:function(filter=null,por_pagina=false){
                var params = {'orderby': this.getOrderBy()};
                if(por_pagina) params['por_pagina'] = -1;
                if(this.pagination.current_page > 0) params['page'] = this.pagination.current_page;

                if(filter) params['filtro'] = filter;
                else if(this.filtro) params['filtro'] = this.filtro;
                
                return params;
            },
            getParametrosSelect:function(filter=null,por_pagina=false){
                var params = {'orderby':  [this.orderby.field, this.orderby.type]};
                if(por_pagina) params['por_pagina'] = -1;
                if(filter) params['filtro'] = filter;
                return params;
            },
            quitarDetalleDialog(detalle){
                Swal({
                    title: 'Confirmación',
                    text: "¿Desea quitar el detalle?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Quitar',
                    cancelButtonText: 'Cancelar',
                    animation: false
                  }).then((result) => {
                    if (result.value) {
                        this.quitarDetalle(detalle);
                    }
                  })
            },
            showToast(title, text='La solicitud se eliminó', icon=null,color='#33AA66'){
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
            cambiarPagina: function(){
                this.getLogs();
            },
            toggleOrderBy:function(field)
            {
                this.orderby.type = (field == this.orderby.field)? !this.orderby.type:true;
                this.orderby.field = field;                
                this.getLogs();
            },
            getOrderBy:function(){
                return [this.orderby.field, this.orderby.type];
            },
            addDetail:function(detalle)
            {
                this.detalles.push(detalle);
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
                }, 300);
            },
            agregarDetalle:function(){
                if(this.isDetailValid(this.detalle_tmp))
                {
                    this.detalle_tmp['id'] = this.detalles.length;      
                    if(!this.detalle_tmp.Recurso.CodigoEDM)
                    {
                        var url = this.api_url+'/inventario/recursos/inventario/'+this.detalle_tmp.Recurso.Codigo+'/recurso/'+this.detalle_tmp.Lote;
                        axios.get(url).then(response => {
                            var data = response.data;
                            if(data)
                            {
                                data = data.data;
                                this.detalle_tmp.Recurso = data;
                                this.addDetail(this.detalle_tmp);
                                this.initDetalle();
                            }
                        });
                                         
                    }
                    else 
                    {
                        this.addDetail(this.detalle_tmp);
                        this.initDetalle();
                    }
                }
                else alert("Por favor verifique los detalles de ingeso");
            },
            quitarDetalle:function(detalle){
                this.detalles.splice(detalle.id, 1);
                this.detalles.forEach(function(element, index){
                    element.id = index;
                });
                this.$forceUpdate();
            },
            isDetailValid:function(detalle){
                var val = (detalle.Recurso && (detalle.Cantidad)? detalle.Cantidad >= 0:false);

                if(val)
                    if(detalle.Recurso.CodigoEDM) return val;
                    val = val && (detalle.Lote != null);
                    
                return val;
            },  
            mayorACero(valor)
            {
                return (valor)? (parseInt(valor) > 0):true;
                //return (parseFloat(valor) > 0);
            },
            showEstadoRecurso(estado)
            {                
                this.estadoRecurso = estado;
                this.$modal.show('estadoRecurso');
            },
        },
        created: function () {            
            if(document.getElementsByClassName("vue-logs-admin-detail").length <= 0 && document.getElementsByClassName("vue-logs-admin-create").length <= 0)
            {
                this.create_devolucion = false;
                this.getLogs();
            }
            if(document.getElementsByClassName("vue-logs-admin-create").length > 0)
            {
                this.create_devolucion = true;
                this.getLogs();
            }
        },
    });
    
}