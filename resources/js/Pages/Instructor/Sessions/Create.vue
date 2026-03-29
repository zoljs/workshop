<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import { Separator } from '@/Components/ui/separator';
import AppLayout from '@/Layouts/AppLayout.vue';
import { goBack } from '@/lib/utils';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Check } from 'lucide-vue-next';

defineProps<{
    workshops: Array<{ id: number; name: string }>;
}>();

const form = useForm({
    workshop_id: '' as string,
    starts_at: '',
    max_capacity: 10,
});
</script>

<template>
    <Head title="Új időpont" />
    <AppLayout>
        <div
            class="mx-auto grid min-h-screen max-w-7xl grid-cols-1 gap-8 bg-background px-4 pb-40 pt-20 sm:px-6 md:pt-32 lg:grid-cols-[1fr_0.4fr] lg:px-8"
        >
            <section class="flex flex-col gap-8">
                <Button @click="goBack" variant="ghost" size="lg" class="w-24">
                    <ArrowLeft />
                    Vissza
                </Button>

                <header>
                    <h1
                        class="max-w-2xl text-4xl font-bold leading-tight text-primary md:text-6xl"
                    >
                        Új időpont
                    </h1>
                </header>

                <Separator />

                <form
                    id="session-create-form"
                    @submit.prevent="
                        form.post(route('instructor.sessions.store'))
                    "
                    class="flex flex-col gap-4"
                >
                    <div>
                        <Label>Workshop</Label>
                        <Select v-model="form.workshop_id">
                            <SelectTrigger>
                                <SelectValue placeholder="Válassz workshopot" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="w in workshops"
                                    :key="w.id"
                                    :value="String(w.id)"
                                >
                                    {{ w.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <p
                            v-if="form.errors.workshop_id"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.workshop_id }}
                        </p>
                    </div>
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
                        <Label>Max létszám</Label>
                        <Input
                            type="number"
                            v-model="form.max_capacity"
                            min="1"
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
                    form="session-create-form"
                    :disabled="form.processing"
                    size="lg"
                    class="h-20"
                >
                    <Check />
                    Időpont hozzáadása
                </Button>
            </aside>
        </div>
    </AppLayout>
</template>
