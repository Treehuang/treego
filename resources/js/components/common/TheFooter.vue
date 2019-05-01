<template>
    <footer class="footer">
        <div class="container">
            <p class="float-left">
                由 <a href="https://github.com/Treehuang" target="_blank">tree</a> 设计和编码
            </p>

            <p class="float-right"><a href="https://github.com/Treehuang" target="_blank">联系我</a></p>
        </div>
    </footer>
</template>

<script>

    export default {
        name: 'TheFooter'
    }

</script>

<style scoped>
    a {
        color: inherit;
        text-decoration: none;
    }

    a:hover {
        color: #4999a2;
    }

    .footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        /* Set the fixed height of the footer here */
        height: 60px;
        background-color: #000;
    }

    p {
        margin: 19px 0;
        color: #c1c1c1;
    }

    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
</style>