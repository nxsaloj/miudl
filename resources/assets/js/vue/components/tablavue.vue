<template>
    <div class="tablavue closed">
      <div class="item-row closed">

        <div class="item-col" v-for="(item,key,index) in values" v-on:click="handleClick($event)" v-if="inKeys(key)" v-bind:class="{ collapsable:(caretindex == index) && (values.ChildCount > 0)}">
          <span class="item-tag">{{ item }} <span v-if="validarSerial(key, values)">(Serial: {{ values['Serial']}})</span> </span>
          <template v-if="values.ChildCount">
            <i v-if="(index == caretindex) && (values.ChildCount > 0)" class="item-icon fa fa-angle-right" v-bind:class="{ caret:(caretindex == index) }"></i>
          </template>            
        </div>         

      </div>
      <template v-if="values.Childs">
          <template v-for="itemc in values.Childs">
          <tablavue class="tablavue-intern" :values="itemc" :caretindex="caretindex" :keys="keys"></tablavue>
        </template>
      </template>
    </div>
</template>

<script type="text/babel">
import tablavue from "./tablavue.vue";
import $ from "jquery";

export default {
  name: "tablavue", // this is what the Warning is talking about.
  components: {
    tablavue: tablavue
  },
  props: {
    values: [Array, Object],
    caretindex: {
      type: Number,
      required: false
    },
    keys: Array
  },
  methods: {
    validarSerial(key, values) {
      if (values) return key == "Nombre" && "Serial" in values;
      else return false;
    },
    inKeys(key) {
      var keys = this.keys;
      if (keys) {
        return keys.includes(key);
      }
      return true;
    },
    handleClick($event) {
      var el = $event.target;
      if (!$(el).hasClass("item-col")) el = $(el).parent();

      if ($(el).hasClass("collapsable")) {
        $(el).toggleClass("closed");
        var child_container = $(el)
          .parent()
          .parent()
          .find(".tablavue-intern");
        child_container.toggleClass("closed");
      }
    }
  }
};
</script>


<style>
.tablavue {
  list-style-type: none;
  margin: 0;
  padding: 0;
  border: 1px solid black;

  display: flex;
  flex-direction: column;
}

.tablavue .tablavue.closed {
  display: none !important;
}

.tablavue .item-row {
  padding: 0;
  margin: 0;

  display: flex;
}

.tablavue-intern {
  flex-basis: 1;
}

.tablavue .closed > i {
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  -o-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  transform: rotate(180deg);
}

.tablavue .item-row .tablavue {
  position: relative;
  padding: 0;
}
.tablavue .item-row {
  width: 100%;
}
.tablavue .item-col.collapsable {
  cursor: pointer;
}
.tablavue .item-col {
  position: relative;
  padding: 5px;
  margin-left: 5px;
  margin-right: 5px;

  flex: 1;
  width: 0;
  margin-right: 10px;
}
.tablavue .item-col {
  color: black !important;
}

.tablavue .item-col:has(i.caret) {
  min-width: 200px !important;
}
</style>