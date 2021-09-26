<template>
    <div>{{load}}</div>
</template>

<script lang="ts">

import axios from 'axios'
import { onMounted } from '@vue/runtime-core';

export default {
   async setup() {
     
    async function loadData() {
        return axios.get('https://jsonplaceholder.typicode.com/posts')
    }

    const data = await loadData();

    console.log(data);

    // This is implicitly wrapped in a promise because
    // the function is `async`
    onMounted(() => {
    })
    return {
      data
    }
  },

  computed: {
    load() {
      return this.data.filter((x)=>{
        return  x.id == 1;
      });
    }
  }
}
</script>
