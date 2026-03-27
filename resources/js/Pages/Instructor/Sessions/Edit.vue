<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Separator } from '@/Components/ui/separator';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Check, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
    session: {
        id: number;
        starts_at: string;
        max_capacity: number;
        booked_count: number;
        workshop: { id: number; name: string };
    };
}>();

function toDatetimeLocal(d: string) {
    const date = new Date(d);
    const pad = (n: number) => String(n).padStart(2, '0');
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
}

function cancelSession() {
    if (
        confirm(
            'Törlöd ezt az időpontot? Az összes foglalás lemondottként lesz jelölve.',
        )
    ) {
        router.patch(route('instructor.sessions.cancel', props.session.id));
    }
}

const form = useForm({
    starts_at: toDatetimeLocal(props.session.starts_at),
    max_capacity: props.session.max_capacity,
});
</script>

<template>
    <Head title="Időpont szerkesztése" />
    <AppLayout>
        <div
            class="mx-auto grid min-h-screen max-w-7xl grid-cols-1 gap-8 bg-background px-4 pb-40 pt-20 sm:px-6 md:pt-32 lg:grid-cols-[1fr_0.4fr] lg:px-8"
        >
            <section class="flex flex-col gap-8">
                <Button as-child variant="ghost" size="lg" class="w-24">
                    <Link :href="route('instructor.workshops.index')">
                        <ArrowLeft />
                        Vissza
                    </Link>
                </Button>

                <header class="flex flex-col gap-1">
                    <h1
                        class="max-w-2xl text-4xl font-bold leading-tight text-primary md:text-6xl"
                    >
                        Időpont szerkesztése
                    </h1>
                    <p class="text-muted-foreground">
                        {{ session.workshop.name }}
                    </p>
                </header>

                <Separator />

                <form
                    id="session-edit-form"
                    @submit.prevent="
                        form.patch(
                            route('instructor.sessions.update', session.id),
                        )
                    "
                    class="flex flex-col gap-4"
                >
                    <div>
                        <Label>Dátum és időpont</Label>
                        <Input type="datetime-local" v-model="form.starts_at" />
                        <p
                            v-if="form.errors.starts_at"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.starts_at }}
                        </p>
                    </div>
                    <div>
                        <Label
                            >Max létszám (min:
                            {{ session.booked_count }})</Label
                        >
                        <Input
                            type="number"
                            v-model="form.max_capacity"
                            :min="session.booked_count"
                            max="100"
                        />
                        <p
                            v-if="form.errors.max_capacity"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.max_capacity }}
                        </p>
                    </div>
                </form>
            </section>

            <aside
                class="flex flex-col gap-4 lg:sticky lg:top-24 lg:self-start"
            >
                <Button
                    type="submit"
                    form="session-edit-form"
                    :disabled="form.processing"
                    size="lg"
                    class="h-20"
                >
                    <Check />
                    Időpont mentése
                </Button>

                <Button
                    variant="destructive"
                    size="lg"
                    class="h-14"
                    @click="cancelSession"
                >
                    <Trash2 />
                    Időpont törlése
                </Button>

                <!-- Booking info card -->
                <div class="rounded-lg border p-4 text-sm">
                    <p class="font-semibold">{{ session.workshop.name }}</p>
                    <p class="text-muted-foreground">
                        Foglalt helyek: {{ session.booked_count }} /
                        {{ session.max_capacity }}
                    </p>
                </div>
            </aside>
        </div>
    </AppLayout>
</template>
