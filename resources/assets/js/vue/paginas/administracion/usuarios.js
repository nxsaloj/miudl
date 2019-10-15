import Vue from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import paginador from '../../components/paginador.vue'
import toastel from '../../components/toastel.vue'

if(document.getElementsByClassName("vue-usuarios").length > 0){
    var vue_emp = new Vue({
        el: ".vue-usuarios",
        components: {
            paginador,
            toastel
        },
        data :
        {
            api_url:'/api/v1',
            url:'/administracion/usuarios/',
            show:false,
            showextra:false,
            usuarios:[],
            pagination:{
                "current_page":0,
                "last_page":0,
                "to":0,
                "per_page":0,
                "total":0
            },
            orderby:{
                "field":'Usuario',
                "type":true
            },
            filtro:null
        },
        methods: {
            //Métodos HTTP
            getUsuarios: function() {
                var url = this.api_url+this.url;
                axios.get(url, {
                    params: this.getParametros()
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.usuarios =  data.data;
                        this.pagination = data;
                        delete this.pagination.data;
                    }
                });
            },
            eliminarUsuario: function(usuario,$event=null) {
                var url = this.api_url+this.url+usuario.id;

                axios.delete(url+(($event)? "?flash=true":"")).then(response => {
                    var data = response.data;
                    if($event) window.location = data.data.Url.replace("api/v1/","");
                    else
                    {
                        this.getUsuarios();
                        this.showToast("Eliminado",data.meta.msj,"success");
                    }
                });
            },
            desactivarUsuario: function(usuario,$event=null) {
                var url = this.api_url+this.url+"desactivar/"+usuario.id;

                axios.post(url+(($event)? "?flash=true":"")).then(response => {
                    var data = response.data;
                    if($event) window.location = data.data.Url.replace("api/v1/","");
                    else
                    {
                        this.getUsuarios();
                        this.showToast("Desactivado",data.meta.msj,"success");
                    }
                });
            },
            reactivarUsuario: function(usuario,$event=null) {
                var url = this.api_url+this.url+"reactivar/"+usuario.id;

                axios.post(url+(($event)? "?flash=true":"")).then(response => {
                    var data = response.data;
                    if($event) window.location = data.data.Url.replace("api/v1/","");
                    else
                    {
                        this.getUsuarios();
                        this.showToast("Reactivado",data.meta.msj,"success");
                    }
                });
            },
            reasignarUsuario: function(usuario,$event=null) {
                var url = this.api_url+this.url+"reasignar/"+usuario.id;

                axios.post(url+(($event)? "?flash=true":"")).then(response => {
                    var data = response.data;
                    if($event) window.location = data.data.Url.replace("api/v1/","");
                    else
                    {
                        this.getUsuarios();
                        this.showToast("Reasignado",data.meta.msj,"success");
                    }
                });
            },
            //Funciones
            limpiar(){
                this.filtro = "";
                this.getUsuarios();
            },
            getParametros:function(){
                var params = {'orderby': this.getOrderBy()};
                if(this.pagination.current_page > 0) params['page'] = this.pagination.current_page;
                if(this.filtro) params['filtro'] = this.filtro;
                return params;
            },
            confirmDialog(usuario, $event=null)
            {
                Swal({
                    title: 'Confirmación',
                    text: "¿Desea eliminar el usuario "+usuario.Usuario+"?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    animation: false
                  }).then((result) => {
                    if (result.value) {
                        this.eliminarUsuario(usuario,$event);
                    }
                  })
            },
            confirmDeactivate(usuario, $event=null)
            {
                Swal({
                    title: (usuario.Deactivated_at)? 'Reactivar':'Desactivar',
                    text: "¿Desea "+((usuario.Deactivated_at)? "Reactivar":"Desactivar")+" el usuario "+usuario.Usuario+"?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: (usuario.Deactivated_at)? 'Reactivar':'Desactivar',
                    cancelButtonText: 'Cancelar',
                    animation: false
                  }).then((result) => {
                    if (result.value) {
                        if(usuario.Deactivated_at) this.reactivarUsuario(usuario, $event);
                        else this.desactivarUsuario(usuario,$event);
                    }
                  })
            },
            confirmReset(usuario, $event=null)
            {
                Swal({
                    title: 'Reasignar',
                    text: "¿Desea reasignar la contraseña al usuario "+usuario.Usuario+", por miudl_2019?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Reasignar',
                    cancelButtonText: 'Cancelar',
                    animation: false
                  }).then((result) => {
                    if (result.value) {
                        this.reasignarUsuario(usuario,$event);
                    }
                  })
            },
            showToast(title, text="El usuario se eliminó", icon,color){
                $.toast({
                    heading: title,
                    text: text,
                    position: String("top-right"),
                    stack: false,
                    loader: false,
                    icon:icon,
                    showHideTransition: 'slide',
                    bgColor: color? color:'#33AA66',
                });
            },
            cambiarPagina: function(){
                this.getUsuarios();
            },
            toggleOrderBy:function(field)
            {
                this.orderby.type = (field == this.orderby.field)? !this.orderby.type:true;
                this.orderby.field = field;
                this.getUsuarios();
            },
            getOrderBy:function(){
                return [this.orderby.field, this.orderby.type];
            },
            session_keep:function(action){
                this.showextra = true;
                setTimeout(function() {
                    $("#extra-action").val(action);
                    $(".submit").trigger( "click" );
                }, 50);
            }
        },
        created: function () {
            if(document.getElementsByClassName("vue-usuarios-detail").length <= 0) this.getUsuarios();
        }
    });
}
