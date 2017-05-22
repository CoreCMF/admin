<template>
  <div class="app-wrapper" >
    <div class="sidebar-wrapper">
      <Sidebar class="sidebar-container"/>
    </div>
    <div class="main-container">
        Navbar-begin<br>
        Navbar-end<br>
        App-main-begin<br>
        <router-view></router-view>
        App-main-end<br>
    </div>
  </div>
</template>
<script>
  import Sidebar from './Sidebar.vue'
  export default {
    components: {
      Sidebar
    },
    computed: {
      sidebar () {
        return false
      }
    }
  }
</script>
<style lang="scss" scoped>
  @import "src/assets/sass/mixin.scss";
  .app-wrapper {
    position: relative;
    height: 100%;
    width: 100%;
    .sidebar-wrapper {
      width: 230px;
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 1001;
      overflow-x: hidden;
      transition: all .28s ease-out;
      @include scrollBar;
    }
    .sidebar-container {
      transition: all .28s ease-out;
    }
    .main-container {
      min-height: 100%;
      transition: all .28s ease-out;
      margin-left: 230px;
    }
  }
</style>
