<template>
    <AuthenticatedLayout>
        <Head :title="`${user.name} - তথ্য সম্পাদনা`" />
        <div class="py-8 min-h-screen bg-gradient-to-br from-[#f6fafb] via-[#f2f7f2] to-[#f1f7ff] dark:from-[#101728] dark:to-[#18222e] transition-all">
            <div class="mx-auto px-2 sm:px-6 lg:px-8">
                <Card class="shadow-2xl border-0 rounded-2xl overflow-visible backdrop-blur-xl bg-white/90 dark:bg-[#1C2536]/80">
                    <template #title>
                        <div class="flex items-center justify-between">
                            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight flex items-center gap-3">
                                <i class="pi pi-user-edit text-emerald-500 text-3xl" />
                                {{ user.name }} - তথ্য সম্পাদনা
                            </h2>
                            <Button
                                label="ফিরে যান"
                                icon="pi pi-arrow-left"
                                class="p-button-text p-button-lg border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-transparent text-gray-700 dark:text-gray-400 hover:bg-emerald-100 hover:text-emerald-700"
                                @click="$inertia.visit(route('admin.users.index'))"
                            />
                        </div>
                    </template>

                    <template #content>
                        <!-- Tabs -->
                        <div class="mb-8 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex">
                                <button
                                    @click="activeTab = 'basic'"
                                    :class="[
                                      'px-6 py-3 text-lg font-semibold transition-all duration-200 focus:outline-none',
                                      activeTab === 'basic'
                                        ? 'border-b-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30'
                                        : 'text-gray-500 hover:text-emerald-700 dark:text-gray-400 dark:hover:text-emerald-400'
                                    ]"
                                >
                                    <i class="pi pi-id-card mr-2" /> বেসিক তথ্য
                                </button>
                                <button
                                    @click="activeTab = 'permissions'"
                                    :class="[
                                      'px-6 py-3 text-lg font-semibold transition-all duration-200 focus:outline-none',
                                      activeTab === 'permissions'
                                        ? 'border-b-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30'
                                        : 'text-gray-500 hover:text-emerald-700 dark:text-gray-400 dark:hover:text-emerald-400'
                                    ]"
                                >
                                    <i class="pi pi-lock mr-2" /> রোল ও পারমিশন
                                </button>
                            </div>
                        </div>

                        <form @submit.prevent="submit" autocomplete="off">
                            <!-- Basic Information Tab -->
                            <div v-if="activeTab === 'basic'" class="space-y-10">
                                <!-- Profile Image -->
                                <div class="flex justify-center">
                                    <div class="text-center">
                                        <div class="w-32 h-40 mx-auto mb-4 overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 via-white to-emerald-100 flex items-center justify-center border-2 border-dashed border-emerald-200 shadow-inner">
                                            <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" />
                                            <img v-else-if="user.profile_image" :src="`/storage/${user.profile_image}`" class="w-full h-full object-cover" />
                                            <div v-else class="text-center">
                                                <i class="pi pi-user text-5xl text-gray-300"></i>
                                                <p class="text-xs text-gray-500 mt-2">প্রোফাইল ছবি</p>
                                            </div>
                                        </div>
                                        <FileUpload
                                            mode="basic"
                                            chooseLabel="ছবি পরিবর্তন করুন"
                                            :customUpload="true"
                                            @uploader="handleImageUpload"
                                            accept="image/*"
                                            class="p-button-outlined p-button-success"
                                        />
                                        <p class="text-xs text-gray-400 mt-2">JPG, PNG ফর্ম্যাট, সর্বোচ্চ 2MB</p>
                                        <small v-if="form.errors.profile_image" class="p-error">{{ form.errors.profile_image }}</small>
                                    </div>
                                </div>

                                <Divider class="my-6" />

                                <!-- Form Fields -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                                    <div class="space-y-2">
                                        <label for="name" class="text-sm font-semibold text-gray-700 dark:text-gray-200">নাম <span class="text-red-500">*</span></label>
                                        <InputText
                                            id="name"
                                            v-model="form.name"
                                            class="w-full"
                                            :class="{'p-invalid': form.errors.name}"
                                            placeholder="পূর্ণ নাম লিখুন"
                                        />
                                        <small v-if="form.errors.name" class="p-error">{{ form.errors.name }}</small>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="email" class="text-sm font-semibold text-gray-700 dark:text-gray-200">ইমেইল <span class="text-red-500">*</span></label>
                                        <InputText
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            class="w-full"
                                            :class="{'p-invalid': form.errors.email}"
                                            placeholder="example@email.com"
                                        />
                                        <small v-if="form.errors.email" class="p-error">{{ form.errors.email }}</small>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="phone" class="text-sm font-semibold text-gray-700 dark:text-gray-200">ফোন নম্বর</label>
                                        <InputText
                                            id="phone"
                                            v-model="form.phone"
                                            class="w-full"
                                            :class="{'p-invalid': form.errors.phone}"
                                            placeholder="01xxxxxxxxx"
                                        />
                                        <small v-if="form.errors.phone" class="p-error">{{ form.errors.phone }}</small>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="nid" class="text-sm font-semibold text-gray-700 dark:text-gray-200">জাতীয় পরিচয়পত্র নম্বর</label>
                                        <InputText
                                            id="nid"
                                            v-model="form.nid"
                                            class="w-full"
                                            :class="{'p-invalid': form.errors.nid}"
                                            placeholder="১০ বা ১৩ সংখ্যার NID"
                                        />
                                        <small v-if="form.errors.nid" class="p-error">{{ form.errors.nid }}</small>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="brn" class="text-sm font-semibold text-gray-700 dark:text-gray-200">জন্ম নিবন্ধন নম্বর</label>
                                        <InputText
                                            id="brn"
                                            v-model="form.brn"
                                            class="w-full"
                                            :class="{'p-invalid': form.errors.brn}"
                                            placeholder="জন্ম নিবন্ধন নম্বর"
                                        />
                                        <small v-if="form.errors.brn" class="p-error">{{ form.errors.brn }}</small>
                                    </div>

                                    <div class="space-y-2 md:col-span-2">
                                        <label for="address" class="text-sm font-semibold text-gray-700 dark:text-gray-200">ঠিকানা</label>
                                        <Textarea
                                            id="address"
                                            v-model="form.address"
                                            rows="3"
                                            class="w-full"
                                            :class="{'p-invalid': form.errors.address}"
                                            placeholder="সম্পূর্ণ ঠিকানা লিখুন"
                                        />
                                        <small v-if="form.errors.address" class="p-error">{{ form.errors.address }}</small>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="password" class="text-sm font-semibold text-gray-700 dark:text-gray-200">নতুন পাসওয়ার্ড</label>
                                        <Password
                                            id="password"
                                            v-model="form.password"
                                            :feedback="true"
                                            :toggleMask="true"
                                            class="w-full"
                                            :class="{'p-invalid': form.errors.password}"
                                            placeholder="নতুন পাসওয়ার্ড (ঐচ্ছিক)"
                                        />
                                        <small v-if="form.errors.password" class="p-error">{{ form.errors.password }}</small>
                                        <small class="text-gray-500">খালি রাখলে পাসওয়ার্ড পরিবর্তন হবে না</small>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="password_confirmation" class="text-sm font-semibold text-gray-700 dark:text-gray-200">পাসওয়ার্ড নিশ্চিতকরণ</label>
                                        <Password
                                            id="password_confirmation"
                                            v-model="form.password_confirmation"
                                            :feedback="false"
                                            :toggleMask="true"
                                            class="w-full"
                                            :class="{'p-invalid': form.errors.password_confirmation}"
                                            placeholder="পাসওয়ার্ড পুনরায় লিখুন"
                                        />
                                        <small v-if="form.errors.password_confirmation" class="p-error">{{ form.errors.password_confirmation }}</small>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-4">
                                    <Button
                                        label="পরবর্তী: রোল ও পারমিশন"
                                        icon="pi pi-arrow-right"
                                        iconPos="right"
                                        @click="activeTab = 'permissions'"
                                        class="p-button-primary p-button-lg shadow-md"
                                    />
                                </div>
                            </div>

                            <!-- Permissions Tab -->
                            <div v-if="activeTab === 'permissions'" class="space-y-8">
                                <!-- Role Selection -->
                                <Card class="rounded-xl shadow-none border border-emerald-100 dark:border-gray-800">
                                    <template #title>
                                        <span class="text-lg font-bold text-emerald-700 dark:text-emerald-300"><i class="pi pi-users mr-2" />রোল নির্বাচন</span>
                                    </template>
                                    <template #content>
                                        <div class="space-y-2">
                                            <label for="role" class="text-sm font-semibold text-gray-700 dark:text-gray-200">প্রধান রোল <span class="text-red-500">*</span></label>
                                            <Dropdown
                                                id="role"
                                                v-model="form.role"
                                                :options="roles"
                                                optionLabel="name"
                                                optionValue="name"
                                                placeholder="রোল নির্বাচন করুন"
                                                class="w-full md:w-1/2"
                                                :class="{'p-invalid': form.errors.role}"
                                            />
                                            <small v-if="form.errors.role" class="p-error">{{ form.errors.role }}</small>
                                            <div v-if="selectedRole" class="mt-2 p-3 bg-emerald-50 dark:bg-emerald-900/40 rounded-lg border border-emerald-200 text-emerald-800 dark:text-emerald-100">
                                                <p class="text-sm">
                                                    <strong>{{ selectedRole.name }}</strong> রোল নির্বাচিত হয়েছে।
                                                    এই রোলের সাথে অতিরিক্ত পারমিশন যোগ করতে পারেন।
                                                </p>
                                            </div>
                                        </div>
                                    </template>
                                </Card>

                                <!-- All Menu Permissions -->
                                <Card class="rounded-xl shadow-none border border-emerald-100 dark:border-gray-800">
                                    <template #title>
                                        <span class="text-lg font-bold text-emerald-700 dark:text-emerald-300"><i class="pi pi-key mr-2" />সব মেনু পারমিশন</span>
                                    </template>
                                    <template #content>
                                        <div class="space-y-6">
                                            <template v-for="menu in [
                                                { key: 'setup',     name: 'সেটাপ সংক্রান্ত',         icon: 'pi pi-cog', perms: ['setup.access', 'setup.marhala', 'setup.subject', 'setup.central_exam', 'setup.user', 'setup.instruction'] },
                                                { key: 'bill',      name: 'ভাতা ও বিল',             icon: 'pi pi-money-bill', perms: ['bill.access', 'bill.negran', 'bill.mumtahin', 'bill.markaz_admin', 'bill.zonal'] },
                                                { key: 'madrasa',   name: 'মাদরাসা সংক্রান্ত',      icon: 'pi pi-building', perms: ['madrasa.access', 'madrasa.list', 'madrasa.misc', 'madrasa.admin', 'madrasa.draft_soft_delete', 'madrasa.payment'] },
                                                { key: 'markaz',    name: 'মারকায সংক্রান্ত',       icon: 'pi pi-building', perms: ['markaz.access', 'markaz.application_list', 'markaz.proposed', 'markaz.list', 'markaz.change_application', 'markaz.admin', 'markaz.admin_training', 'markaz.group'] },
                                                { key: 'registration',name: 'নিবন্ধন সংক্রান্ত',    icon: 'pi pi-user-plus', perms: ['registration.access', 'registration.list', 'registration.payment', 'registration.card_create'] },
                                                { key: 'inclusion', name: 'অন্তর্ভুক্তি সংক্রান্ত',  icon: 'pi pi-user-edit', perms: ['inclusion.access', 'inclusion.list', 'inclusion.roll_generate', 'inclusion.admit_card'] },
                                                { key: 'negran',    name: 'নেগরান সংক্রান্ত',       icon: 'pi pi-users', perms: ['negran.access', 'negran.application_list', 'negran.proposed_list', 'negran.pending_list', 'negran.cancelled_list'] },
                                                { key: 'mumtahin',  name: 'মুমতাহিন সংক্রান্ত',     icon: 'pi pi-user-edit', perms: ['mumtahin.access', 'mumtahin.application_list', 'mumtahin.proposed_list', 'mumtahin.inspector_setup'] },
                                                { key: 'zone',      name: 'জোন সংক্রান্ত',          icon: 'pi pi-map-marker', perms: ['zone.access', 'zone.setup', 'zone.selection', 'zone.sub_zone_setup'] },
                                                { key: 'attendance',name: 'হাজিরা সংক্রান্ত',       icon: 'pi pi-check-square', perms: ['attendance.access', 'attendance.examinee', 'attendance.negran'] },
                                                { key: 'result',    name: 'ফলাফল সংক্রান্ত',        icon: 'pi pi-chart-bar', perms: ['result.access', 'result.condition', 'result.merit_condition', 'result.inspection_formula'] },
                                                { key: 'communication', name: 'যোগাযোগ',           icon: 'pi pi-comment', perms: ['communication.access', 'communication.messaging', 'communication.notice'] }
                                            ]" :key="menu.key">
                                                <div class="border rounded-lg overflow-hidden bg-white dark:bg-[#22293b] shadow-sm mb-5">
                                                    <div class="bg-gradient-to-r from-emerald-50 via-white to-emerald-100 dark:from-emerald-900/60 dark:to-[#22293b] px-4 py-3 border-b flex justify-between items-center">
                                                        <div class="flex items-center space-x-3">
                                                            <input
                                                                type="checkbox"
                                                                :id="`module_${menu.key}`"
                                                                :checked="menu.perms.every(p => form.permissions.includes(p)) && menu.perms.length > 0"
                                                                @change="(e) => toggleModulePermissions({permissions: menu.perms.map(name => ({name}))}, e.target.checked)"
                                                                class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            />
                                                            <i :class="menu.icon" class="text-lg text-emerald-600 dark:text-emerald-300"></i>
                                                            <label :for="`module_${menu.key}`" class="text-lg font-semibold text-gray-700 dark:text-gray-100 cursor-pointer">
                                                                {{ menu.name }}
                                                            </label>
                                                        </div>
                                                        <small class="text-gray-400">{{ menu.perms.length }} টি পারমিশন</small>
                                                    </div>
                                                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 bg-white dark:bg-[#232a3d]">
                                                        <div v-for="perm in menu.perms" :key="perm" class="flex items-center space-x-2">
                                                            <input
                                                                type="checkbox"
                                                                :id="`permission_${menu.key}_${perm}`"
                                                                :checked="form.permissions.includes(perm)"
                                                                @change="togglePermission(perm)"
                                                                class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            />
                                                            <label :for="`permission_${menu.key}_${perm}`" class="text-sm cursor-pointer text-gray-700 dark:text-gray-200">
                                                                {{ perm }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <small v-if="form.errors.permissions" class="p-error">{{ form.errors.permissions }}</small>
                                    </template>
                                </Card>

                                <div class="flex justify-between pt-4">
                                    <Button
                                        label="পূর্ববর্তী: বেসিক তথ্য"
                                        icon="pi pi-arrow-left"
                                        @click="activeTab = 'basic'"
                                        class="p-button-secondary p-button-lg"
                                    />
                                    <Button
                                        label="তথ্য আপডেট করুন"
                                        icon="pi pi-check"
                                        iconPos="right"
                                        type="submit"
                                        :loading="form.processing"
                                        class="p-button-success p-button-lg shadow-lg"
                                    />
                                </div>
                            </div>
                        </form>
                    </template>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import Textarea from 'primevue/textarea'
import Password from 'primevue/password'
import Button from 'primevue/button'
import FileUpload from 'primevue/fileupload'
import Checkbox from 'primevue/checkbox'
import Card from 'primevue/card'
import Divider from 'primevue/divider'

const props = defineProps({
    user: Object,
    roles: Array,
    modules: Array,
    userPermissions: Array,
    userRoles: Array
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    phone: props.user.phone || '',
    nid: props.user.nid || '',
    brn: props.user.brn || '',
    address: props.user.address || '',
    role: props.userRoles.length > 0 ? props.userRoles[0] : '',
    permissions: [...props.userPermissions],
    profile_image: null,
})

const imagePreview = ref(null)
const activeTab = ref('basic')

const selectedRole = computed(() => {
    return props.roles.find(role => role.name === form.role)
})

const handleImageUpload = (event) => {
    const file = event.files[0]
    if (file) {
        form.profile_image = file
        const reader = new FileReader()
        reader.onload = (e) => {
            imagePreview.value = e.target.result
        }
        reader.readAsDataURL(file)
        event.options.clear()
    }
}

const toggleModulePermissions = (module, value) => {
    const modulePermissions = module.permissions.map(p => p.name)

    if (value) {
        modulePermissions.forEach(permission => {
            if (!form.permissions.includes(permission)) {
                form.permissions.push(permission)
            }
        })
    } else {
        form.permissions = form.permissions.filter(
            permission => !modulePermissions.includes(permission)
        )
    }
}

const togglePermission = (permission) => {
    const index = form.permissions.indexOf(permission)
    if (index > -1) {
        form.permissions.splice(index, 1)
    } else {
        form.permissions.push(permission)
    }
}

const submit = () => {
    form.put(route('admin.users.update', props.user.id), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<style scoped>
.p-error {
    color: #ef4444 !important;
}
</style>
