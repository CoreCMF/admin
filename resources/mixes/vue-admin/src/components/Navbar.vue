<template>
  <div class="navbar-container" >
    <el-menu :default-active="1" class="el-menu-demo" mode="horizontal" @select="handleSelect">
      <el-menu-item index="1">处理中心</el-menu-item>
      <el-submenu index="2">
        <template slot="title">我的工作台</template>
        <el-menu-item index="2-1">选项1</el-menu-item>
        <el-menu-item index="2-2">选项2</el-menu-item>
        <el-menu-item index="2-3">选项3</el-menu-item>
      </el-submenu>
    </el-menu>
  </div>
</template>
<script>
  export default {

  }
</script>
<style lang="scss" scoped>
  .navbar-container {
    background: #fff;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
    height: 60px;
    transform: translate(-10px, 0);
    padding-left: 20px;
    position: fixed;
    top: 0;
    width: 100%;
  }
  .el-menu{
    background-color: #fff !important;
  }
</style>
