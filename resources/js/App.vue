<template>
    <v-app>
        <div v-if="!$store.state.isAuthenticated && !$store.state.user"
             class="d-flex justify-center align-center" style="height: 100vh;">
            <v-progress-circular indeterminate color="primary"/>
        </div>

        <v-navigation-drawer v-else-if="$store.state.isAuthenticated" class="bg-light-subtle">
            <v-list bgColor="navy">
                <v-list-item>
                    <template v-slot:prepend>
                        <v-img src="/logo.png" width="48" height="48" class="me-2"/>
                    </template>
                    <template v-slot:title>
                        <span class="text-h5 text-teal font-weight-bold text-uppercase">Dubai</span>
                    </template>
                    <template v-slot:subtitle>
                        <span class="text-mint text-uppercas text-body-2">Socials & Businesses</span>
                    </template>
                </v-list-item>
            </v-list>
            <v-list density="compact" nav base-color="navy" variant="elevated" class="fw-bold" activeClass="bg-teal">
                <v-list-item to="/sadmin/dashboard">Dashboard</v-list-item>
                <v-list-item to="/sadmin/users">Users</v-list-item>
                <v-list-item to="/sadmin/gallery">Gallery</v-list-item>
                <v-list-item to="/sadmin/events">Events</v-list-item>
                <v-list-item to="/sadmin/blogs">Blogs</v-list-item>
                <v-list-item to="/sadmin/reviews">Reviews</v-list-item>
                <v-list-item to="/sadmin/partners">Partners</v-list-item>
                <v-divider></v-divider>
                <v-list-item @click="logout" title="Logout" prepend-icon="mdi-logout" />
            </v-list>

        </v-navigation-drawer>
        <v-main v-if="$store.state.isAuthenticated" class="py-4 bg-light-subtle">
            <router-view/>
        </v-main>
    </v-app>
</template>
<script>
export default {
    name:"App",
    async mounted(){
        await this.$store.dispatch('checkAuth')
    },
    methods: {
        logout() {
            this.$store.dispatch('logout')
            window.location.href = '/login'
        }
    }
}

</script>

<style>
.v-application {
    font-family: 'Poppins', 'Roboto', sans-serif !important;
}
/* Typography classes override */
.v-application .text-h1,
.v-application .text-h2,
.v-application .text-h3,
.v-application .text-h4,
.v-application .text-h5,
.v-application .text-h6,
.v-application .headline,
.v-application .title,
.v-application .subtitle-1,
.v-application .subtitle-2,
.v-application .body-1,
.v-application .body-2,
.v-application .caption,
.v-application .button {
    font-family: 'Poppins', 'Roboto', sans-serif !important;
}
.v-data-table__td.v-data-table-column--align-start.v-data-table__th {
    background: #00bfa6;
    color: #000;
    font-weight: 600;
    text-transform: capitalize;
    height: 32px;
}
</style>
