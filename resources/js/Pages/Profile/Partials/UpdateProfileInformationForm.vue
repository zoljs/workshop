<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <form
        @submit.prevent="form.patch(route('profile.update'))"
        class="space-y-6"
    >
        <div class="space-y-2">
            <Label for="name">Név</Label>
            <Input
                id="name"
                v-model="form.name"
                type="text"
                autocomplete="name"
                autofocus
            />
            <p v-if="form.errors.name" class="text-sm text-destructive">
                {{ form.errors.name }}
            </p>
        </div>

        <div class="space-y-2">
            <Label for="email">Email</Label>
            <Input
                id="email"
                v-model="form.email"
                type="email"
                autocomplete="username"
            />
            <p v-if="form.errors.email" class="text-sm text-destructive">
                {{ form.errors.email }}
            </p>
        </div>

        <div v-if="mustVerifyEmail && user.email_verified_at === null">
            <p class="text-sm text-muted-foreground">
                Az email címed nincs megerősítve.
                <Link
                    :href="route('verification.send')"
                    method="post"
                    as="button"
                    class="underline hover:text-foreground"
                >
                    Kattints ide az ellenőrző email újraküldéséhez.
                </Link>
            </p>
            <p
                v-if="status === 'verification-link-sent'"
                class="mt-2 text-sm text-green-600"
            >
                Egy új ellenőrző linket küldtünk az email címedre.
            </p>
        </div>

        <span class="flex flex-1" />

        <div class="flex items-center gap-4">
            <Button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Mentés...' : 'Mentés' }}
            </Button>
            <Transition
                enter-active-class="transition ease-in-out"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out"
                leave-to-class="opacity-0"
            >
                <p
                    v-if="form.recentlySuccessful"
                    class="text-sm text-muted-foreground"
                >
                    Mentve.
                </p>
            </Transition>
        </div>
    </form>
</template>
