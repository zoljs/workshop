<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    status?: string;
}>();

const form = useForm({});

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email megerősítése" />

        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-primary">
                    Email megerősítése
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Regisztráció előtt erősítsd meg az email címedet a kiküldött
                    linkre kattintva. Ha nem kaptad meg az emailt, küldünk egy
                    újat.
                </p>
            </div>

            <p
                v-if="verificationLinkSent"
                class="text-sm font-medium text-green-600"
            >
                Egy új megerősítő linket küldtünk az email címedre.
            </p>

            <form
                @submit.prevent="form.post(route('verification.send'))"
                class="space-y-4"
            >
                <Button
                    type="submit"
                    class="w-full"
                    :disabled="form.processing"
                >
                    {{
                        form.processing
                            ? 'Küldés...'
                            : 'Megerősítő email újraküldése'
                    }}
                </Button>

                <Button as-child variant="outline" class="w-full">
                    <Link :href="route('logout')" method="post" as="button">
                        Kijelentkezés
                    </Link>
                </Button>
            </form>
        </div>
    </GuestLayout>
</template>
