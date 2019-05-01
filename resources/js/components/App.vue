<template>
    <div>
        <the-header></the-header>

        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>

        <the-footer></the-footer>
    </div>
</template>

<script>

    import TheHeader from './common/TheHeader'
    import TheFooter from './common/TheFooter'

    export default {
        components: {
            TheHeader,
            TheFooter,
        },
    }
</script>