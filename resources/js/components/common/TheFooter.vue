<template>
    <footer class="footer">
        <div class="container">
            <p class="float-left">
                由 <a href="https://github.com/Treehuang" target="_blank">tree</a> 设计和编码
            </p>

            <p class="float-right contact" @click="contact">联系我</p>
        </div>
    </footer>
</template>

<script>

    export default {
        name: 'TheFooter',

        methods: {
            contact() {
                this.$swal.fire({
                    text: '添加记得备注~',
                    imageUrl: 'http://192.168.5.105/images/my/me.jpg',
                    width: '360px',
                    padding: '10px',
                    imageWidth: 300,
                    imageHeight: 300,
                    imageAlt: 'tree\'s weichat',
                    animation: false,
                    allowOutsideClick: false,
                })
            }
        }
    }

</script>

<style scoped>
    a {
        color: inherit;
        text-decoration: none;
    }

    a:hover {
        color: #ffffff;
    }

    .footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        /* Set the fixed height of the footer here */
        height: 60px;
        background-color: #1b1c1d;
    }

    p {
        margin: 19px 0;
        color: #c1c1c1;
    }

    .container {
        padding-left: 15px;
        padding-right: 15px;
    }

    .contact:hover {
        color: #ffffff;
        cursor: pointer;
    }
</style>