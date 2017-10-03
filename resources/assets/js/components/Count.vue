<template>
  <div>
    <h1> 这个页面是 1号页面 </h1>
    {{points}} <br/>
    <input type='button' @click='increase' value='点击增加1'/><br/>
  </div>
</template>

<script>
import store from '.././vuex/store'
import { INCREASE } from '.././vuex/mutation_types'
export default {
  computed: {
    points() {
      return store.getters.get_points
    }
  },
  store,
  methods: {
    increase() {
      store.commit(INCREASE,22)
    }
  }
}
</script>