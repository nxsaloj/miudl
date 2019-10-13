<template>
    <ul class="pagination">
        <li class="page-item first" v-if="first_last" v-bind:class="{ disabled:(pagination.current_page <= 1)}">
            <span @click.prevent="changePage(1)" class="page-link">Primero</span>
        </li>
        <li class="page-item prev" v-bind:class="{ disabled:(pagination.current_page <= 1)}">
            <span @click.prevent="changePage(pagination.current_page -1)" class="page-link">Anterior</span>
        </li>
        <li class="page-item" v-for="pagina in pagesNumber" v-bind:class="{ active:(pagina == pagination.current_page)}">
            <span @click.prevent="changePage(pagina)" class="page-link" href="#">{{pagina}}</span>
        </li>
        <li class="page-item next" v-bind:class="{ disabled:(pagination.current_page >= pagination.last_page)}">
            <span @click.prevent="changePage(pagination.current_page +1)" class="page-link">Siguiente</span>
        </li>
        <li class="page-item last" v-if="first_last" v-bind:class="{ disabled:(pagination.current_page >= pagination.last_page)}">
            <span href="" @click.prevent="changePage(pagination.last_page)" class="page-link">Último</span>
        </li>
    </ul>
</template>

<script type="text/babel">
import $ from "jquery";

export default {
  name: "paginador", // this is what the Warning is talking about.
  model: {
    event: 'pagechange'
  },
  props: {
    first_last:Boolean, 
    pagination: [Array, Object]
  },
  methods: {
    changePage: function(pagina){
        this.pagination.current_page = pagina;
        this.$emit('pagechange',this.pagination.current_page);
    }
    
  },
  computed: {
      isActivated:function(){
          return this.pagination.current_page;
      },
      pagesNumber:function(){
          var offset = 2;
          if(!this.pagination.to) return [];
          var from = this.pagination.current_page - offset;
          if(from < 1) from = 1;

          var to = from + (offset*2);
          if(to >= this.pagination.last_page) to = this.pagination.last_page;
          
          
          var pagesArray = [];
          while(from <= to)
          {
              pagesArray.push(from);
              
              from++;
          }
          return pagesArray;
      }
  }
};
</script>