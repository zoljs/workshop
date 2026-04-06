<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});
</script>

<template>
    <GuestLayout>
        <Head title="Jelszó visszaállítása" />

        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-primary">
                    Jelszó visszaállítása
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Add meg az új jelszavadat.
                </p>
            </div>

            <form
                @submit.prevent="
                    form.post(route('password.store'), {
                        onFinish: () =>
                            form.reset('password', 'password_confirmation'),
                    })
                "
                class="space-y-4"
            >
                <div class="space-y-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <p
                        v-if="form.errors.email"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <div class="space-y-2">
                    <Label for="password">Új jelszó</Label>
                    <Input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />
                    <p
                        v-if="form.errors.password"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <div class="space-y-2">
                    <Label for="password_confirmation"
                        >Jelszó megerősítése</Label
                    >
                    <Input
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <p
                        v-if="form.errors.password_confirmation"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.password_confirmation }}
                    </p>
                </div>

                <Button
                    type="submit"
                    class="w-full"
                    :disabled="form.processing"
                >
                    {{
                        form.processing
                            ? 'Visszaállítás...'
                            : 'Jelszó visszaállítása'
                    }}
                </Button>
            </form>
        </div>
    </GuestLayout>
</template>
