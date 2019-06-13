<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
        <div class="container">
            <!-- Branding Image -->
            <router-link class="navbar-brand logo" to="/">
                <i class="fas fa-tree"></i>
                <span class="tree">
                    Tree Go
                </span>
            </router-link>

            <!--页面缩小时的按钮-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar">
                <!-- Left Side Of Navbar -->
                <left-side></left-side>

                <the-entry></the-entry>

            </div>

        </div>
    </nav>
</template>

<script>

    import TheEntry from './TheEntry';
    import LeftSide from './LeftSide';

    export default {
        name: 'TheHeader',

        components: {
            TheEntry,
            LeftSide,
        }
    }

</script>

<style scoped>
    .navbar-toggler {
        font-size: 4px;
    }

    .navbar-static-top {
        border-color: #e7e7e7;
        background-color: #fff;
        box-shadow: 0 1px 11px 2px rgba(42, 42, 42, 0.1);
        border-top: 4px solid #5ab7c3;
        border-bottom: 1px solid #e8e8e8;
        margin-bottom: 15px;
        margin-top: 0;
        padding-top: 2px;
        padding-bottom: 1px;
        min-width: 1100px;
    }

    .fa-tree {
        color: #45555d;
        font-style: italic;
    }

    .logo {
        width: 85px;
        margin-right: 30px;
    }

    .logo:hover span {
        color: #438191;
        font-weight: bold;
    }

    .tree {
        color: #45555d;
    }
</style>