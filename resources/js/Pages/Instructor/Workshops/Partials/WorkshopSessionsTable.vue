<script setup lang="ts">
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Card } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import { diffForHumans } from '@/lib/utils';
import { router } from '@inertiajs/vue3';
import { Check, Pencil, Plus, X } from 'lucide-vue-next';
import { ref } from 'vue';
import EditSessionModal from './EditSessionModal.vue';

type Session = {
    id: number;
    starts_at: string;
    max_capacity: number;
    status: string;
    bookings_sum_headcount: number | null;
    update_url: string;
    cancel_url: string;
};

const props = defineProps<{
    workshop: {
        id: number;
        name: string;
        archived: boolean;
        sessions: Session[];
    };
    sessionStoreUrl: string;
}>();

function statusVariant(
    status?: string,
): 'default' | 'secondary' | 'destructive' | 'outline' {
    switch (status) {
        case 'active':
            return 'default';
        case 'cancelled':
            return 'destructive';
        default:
            return 'secondary';
    }
}

function statusLocalization(status?: string): string {
    switch (status) {
        case 'active':
            return 'Aktív';
        case 'cancelled':
            return 'Megszünt';
        default:
            return '-';
    }
}

// Add session form
const showAddForm = ref(false);
const newSession = ref({ starts_at: '', max_capacity: 10 });

function toggleAddForm() {
    showAddForm.value = !showAddForm.value;
    if (!showAddForm.value) {
        newSession.value = { starts_at: '', max_capacity: 10 };
    }
}

function submitNewSession() {
    router.post(
        props.sessionStoreUrl,
        {
            workshop_id: props.workshop.id,
            starts_at: newSession.value.starts_at,
            max_capacity: newSession.value.max_capacity,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                showAddForm.value = false;
                newSession.value = { starts_at: '', max_capacity: 10 };
            },
        },
    );
}

// Edit modal
const editModal = ref<InstanceType<typeof EditSessionModal> | null>(null);
</script>

<template>
    <Card class="overflow-hidden">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead class="w-2/4">Dátum</TableHead>
                    <TableHead class="w-1/5">Helyek</TableHead>
                    <TableHead class="w-1/5">Státusz</TableHead>
                    <TableHead class="w-1/5" />
                </TableRow>
            </TableHeader>

            <TableBody v-if="workshop.sessions.length > 0">
                <TableRow
                    v-for="session in workshop.sessions"
                    :key="session.id"
                >
                    <TableCell>{{
                        diffForHumans(session.starts_at)
                    }}</TableCell>
                    <TableCell
                        >{{ session.max_capacity }} /
                        {{ session.bookings_sum_headcount ?? 0 }}</TableCell
                    >
                    <TableCell>
                        <Badge :variant="statusVariant(session.status)">
                            {{ statusLocalization(session.status) }}
                        </Badge>
                    </TableCell>
                    <TableCell class="flex gap-2 justify-self-end text-right">
                        <Button
                            variant="ghost"
                            size="sm"
                            v-if="session.status !== 'cancelled'"
                            @click="editModal?.openModal(session)"
                        >
                            <Pencil />
                        </Button>
                    </TableCell>
                </TableRow>

                <!-- Add session form row -->
                <TableRow v-if="showAddForm">
                    <TableCell :colSpan="5">
                        <div
                            class="grid grid-cols-[2fr_1fr] gap-4 md:grid-cols-[2fr_1fr_2fr]"
                        >
                            <Input
                                type="datetime-local"
                                v-model="newSession.starts_at"
                            />
                            <Input
                                type="number"
                                v-model="newSession.max_capacity"
                                min="1"
                                max="100"
                            />
                            <div
                                class="col-span-2 flex flex-row items-center justify-center gap-2 md:col-span-1 md:justify-end"
                            >
                                <Button
                                    class="w-full md:w-min"
                                    size="sm"
                                    @click="submitNewSession"
                                >
                                    <Check />
                                </Button>
                                <Button
                                    class="w-full md:w-min"
                                    variant="ghost"
                                    size="sm"
                                    @click="toggleAddForm"
                                >
                                    <X />
                                </Button>
                            </div>
                        </div>
                    </TableCell>
                </TableRow>

                <!-- Add session initiator -->
                <TableRow v-if="!showAddForm && !workshop.archived">
                    <TableCell :colSpan="5">
                        <Button
                            class="h-9 w-full"
                            variant="ghost"
                            size="sm"
                            @click="toggleAddForm"
                        >
                            <Plus />
                            Új időpont hozzáadása
                        </Button>
                    </TableCell>
                </TableRow>
            </TableBody>

            <TableBody v-else>
                <TableRow>
                    <TableCell
                        :colSpan="5"
                        class="text-center text-muted-foreground"
                    >
                        Nincsenek még időpontok.
                    </TableCell>
                </TableRow>
                <TableRow v-if="!workshop.archived">
                    <TableCell :colSpan="5">
                        <Button
                            class="h-9 w-full"
                            variant="ghost"
                            size="sm"
                            @click="toggleAddForm"
                        >
                            <Plus />
                            Új időpont hozzáadása
                        </Button>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </Card>

    <!-- Accessing the edit modal's open function via exposition -->
    <EditSessionModal ref="editModal" />
</template>
