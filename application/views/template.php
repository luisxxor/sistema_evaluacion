<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?= isset($title) ? $title : 'Esteno' ?></title>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js" integrity="sha256-Qfxgn9jULeGAdbaeDjXeIhZB3Ra6NCK3dvjwAG8Y+xU=" crossorigin="anonymous"></script>
  <link href="<?= asset_url().'css/main.css' ?>" rel="stylesheet">
</head>
<body>
  <header id="header">
    <nav>
      <a href="#" id="hamburger-menu" class="sidenav-trigger"><i class="material-icons">menu</i></a>
      <a href="<?= base_url('/') ?>" class="brand-logo">Esteno</a>
      <ul class="links">        
        <li><a href="<?= base_url('workers/') ?>">Trabajadores</a></li>
        <li><a href="<?= base_url('positions/') ?>">Cargos</a></li>
        <li><a href="<?= base_url('areas/') ?>">Areas</a></li>
        <li><a href="<?= base_url('users/listado') ?>">Usuarios</a></li>
        <li id="logoutButton"><a class="logout-button" href="<?=base_url('/users/logout')?>">Cerrar Sesión</a></li>
      </ul>
    </nav>
  </header>
  <aside id="aside">
    <nav>
      <ul class="links" class="sidenav grey darken-4 white-text">
        <li><a class="white-text" href="<?= base_url('/') ?>">Esteno</a></li>
        <li><a href="<?= base_url('workers/') ?>">Trabajadores</a></li>
        <li><a href="<?= base_url('positions/') ?>">Cargos</a></li>
        <li><a href="<?= base_url('areas/') ?>">Areas</a></li>
        <li><a href="<?= base_url('users/listado') ?>">Usuarios</a></li>
        <li class="red darken-1"><a class="white-text" href="<?=base_url('users/logout')?>">Cerrar sesión</a></li>
      </ul>
    </nav>
  </aside>

  <section id="main-content">
    <?PHP $this->load->view($content); ?>
  </section>

</body>

<style>
  body {
    max-height: 100vh;
  }

  .brand-logo {
    margin-left: 1em;
  }

  @media (max-width: 600px) {
    .brand-logo {
      margin: 0;
    }
  }
</style>
<script src="<?= asset_url().'js/main.js' ?>">
</script>
</html>