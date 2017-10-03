<template>
  <div class='logo'>
    <img src="/img/logo.png">
    <h2>{{ title }}</h2>
  </div>
</template>
<script>
  export default {
    props: ['title']
  }
</script>