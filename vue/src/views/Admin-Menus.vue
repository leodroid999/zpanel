<style>
.card-icon{
    text-align: center;
}

.card-link > a{
    text-align: center;
    text-decoration: none !important;
    font-size: 20px;
}
</style>

<script>
export default {

}
</script>
<template>
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">東-panel</a></li>
        <li class="breadcrumb-item active">Admin Menus</li>
    </ul>
    <h4 class="page-header">
        Administrator Menus<small></small>
    </h4>
    <br>
    <p>

    <div class="row">
        <div class="col-sm-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="card-icon">
                        <span><i class="bi bi-clipboard" style="font-size: 70px;"></i></span>
                    </div>
                    <div class="card-link">
                        <RouterLink to="/adminpanels">
                            <div style="text-decoration: none !important;" >Manage Panels</div>
                        </RouterLink>
                    </div>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="card-icon">
                        <span><i class="bi bi-braces" style="font-size: 70px;"></i></span>
                    </div>
                    <div class="card-link">
                        <RouterLink to="/scripts">
                            <div>Manage Scripts</div>
                        </RouterLink>
                    </div>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="card-icon">
                        <span><i class="bi bi-people" style="font-size: 70px;"></i></span>
                    </div>
                    <div class="card-link">
                        <RouterLink to="/adminusers">
                            <div>Manage Users</div>
                        </RouterLink>
                    </div>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>
    </div>

    </p>
</template>