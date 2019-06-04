<template>
    <div class="container">

        <side-bar></side-bar>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">账号绑定</h5>

                <hr>

                <update-email></update-email>

                <hr>

                <update-phone></update-phone>

                <hr>

                <update-password></update-password>

                <hr>

                <third-account></third-account>

            </div>
        </div>

    </div>
</template>

<script>
    import SideBar from './SideBar';
    import UpdatePhone from './UpdatePhone';
    import UpdateEmail from './UpdateEmail';
    import ThirdAccount from './ThirdAccount';
    import UpdatePassword from './UpdatePassword';

    export default {
        components: {
            SideBar,
            UpdatePhone,
            UpdateEmail,
            UpdatePassword,
            ThirdAccount,
        },
    }
</script>

<style scoped>
    .container {
        min-width: 1100px;
    }

    .card {
        border: none;
        background-color: #f8fafc
    }

    .card-body {
        width: 800px;
    }

    hr {
        border-top: 1px solid rgb(222, 233, 234);
    }
</style>