<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Separator } from '@/Components/ui/separator';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Profil" />

    <AppLayout>
        <div
            class="mx-auto max-w-7xl space-y-12 px-4 pb-40 pt-20 sm:px-6 md:pt-32 lg:px-8"
        >
            <!-- Header -->
            <header class="flex max-w-2xl flex-col justify-between gap-4">
                <h1
                    class="text-4xl font-bold leading-tight text-primary md:text-6xl"
                >
                    Profil beállítások
                </h1>
                <p class="mt-1 text-muted-foreground">
                    Kezeld a fiókodat és a személyes adataidat.
                </p>
            </header>

            <Separator />

            <section class="flex flex-row gap-6">
                <Card class="flex-1">
                    <CardHeader>
                        <CardTitle>Profil adatok</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </CardContent>
                </Card>

                <Card class="flex-1">
                    <CardHeader>
                        <CardTitle>Jelszó módosítása</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <UpdatePasswordForm />
                    </CardContent>
                </Card>
            </section>

            <Card>
                <CardHeader>
                    <CardTitle class="text-destructive">Fiók törlése</CardTitle>
                </CardHeader>
                <CardContent>
                    <DeleteUserForm />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
