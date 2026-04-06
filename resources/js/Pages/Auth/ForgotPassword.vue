<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});
</script>

<template>
    <GuestLayout>
        <Head title="Elfelejtett jelszó" />

        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-primary">
                    Elfelejtett jelszó
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Add meg az email címedet és küldünk egy jelszó-visszaállító
                    linket.
                </p>
            </div>

            <p v-if="status" class="text-sm font-medium text-green-600">
                {{ status }}
            </p>

            <form
                @submit.prevent="form.post(route('password.email'))"
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

                <Button
                    type="submit"
                    class="w-full"
                    :disabled="form.processing"
                >
                    {{
                        form.processing
                            ? 'Küldés...'
                            : 'Visszaállító link küldése'
                    }}
                </Button>
            </form>
        </div>
    </GuestLayout>
</template>
