<template>
    <main class="main-content main-content-bg mt-0">
        <div class="page-header min-vh-100" style="
          background-image: url('https://s3-hcm-r1.longvan.net/19403879-letlearn/images/backgroud.png');
        ">
            <span class="mask bg-gradient-dark opacity-5"></span>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-7 mt-2">
                        <div class="card border-0 mb-0">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
