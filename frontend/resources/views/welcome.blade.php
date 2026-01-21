<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAS Backend</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

<div id="app" class="max-w-4xl mx-auto space-y-8 font-sans">
    
    <div class="text-center border-b pb-6 border-gray-300">
        <h1 class="text-3xl font-extrabold text-blue-700 tracking-tight">Sistem Order Mikroservis</h1>
    </div>

    <div v-if="!isLoggedIn" class="bg-white p-8 rounded-xl shadow-lg border border-gray-100 max-w-md mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Silakan Login</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input v-model="loginForm.email" type="email" placeholder="mahasiswa@amikom.ac.id" 
                       class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input v-model="loginForm.password" type="password" placeholder="********" 
                       class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </div>
            <button @click="handleLogin" 
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-200 shadow-md">
                Login
            </button>
        </div>
    </div>

    <div v-else class="animate-fade-in-up space-y-6">
        
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold">
                    @{{ user.email.charAt(0).toUpperCase() }}
                </div>
                <div>
                    <p class="text-sm text-gray-500">Login sebagai:</p>
                    <p class="font-bold text-gray-800">@{{ user.email }} <span class="text-xs font-normal text-gray-500">(ID: @{{ user.id }})</span></p>
                </div>
            </div>
            <button @click="isLoggedIn = false" class="text-red-500 hover:text-red-700 font-medium text-sm border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition">
                Logout
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center gap-2">
                    <span></span> Daftar Produk
                </h2>
                <div class="space-y-3">
                    <div v-for="product in products" :key="product.id" 
                         class="group border border-gray-200 p-4 rounded-lg hover:border-blue-400 hover:shadow-md transition cursor-pointer bg-gray-50 hover:bg-white flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800">@{{ product.name }}</h3>
                            <p class="text-sm text-gray-500">ID: @{{ product.id }}</p>
                        </div>
                        <button @click="createOrder(product.id, product.name)" 
                                class="bg-gray-800 text-white text-sm px-4 py-2 rounded-lg group-hover:bg-blue-600 transition shadow-sm">
                            Pesan
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 h-fit">
                <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center gap-2">
                    <span></span> Riwayat Order
                </h2>
                
                <div v-if="orderLogs.length === 0" class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <p class="text-gray-400">Belum ada pesanan dibuat.</p>
                </div>

                <ul v-else class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                    <li v-for="(log, index) in orderLogs" :key="index" 
                        class="p-3 bg-green-50 border border-green-100 rounded-lg text-sm shadow-sm animate-pulse-once">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-bold text-green-700 block">Order Berhasil! ✅</span>
                                <span class="text-gray-600">Item: <b>@{{ log.productName }}</b></span>
                            </div>
                            <span class="bg-white px-2 py-1 rounded text-xs text-gray-500 border">ID: @{{ log.orderId }}</span>
                        </div>
                        <div class="mt-1 text-xs text-gray-400">Status: Terkirim ke Order Service</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    const { createApp, ref, reactive } = Vue;

    createApp({
        setup() {
            // --- STATE MANAGEMENT (Poin 4d) ---
            const isLoggedIn = ref(false);
            const user = ref({});
            const loginForm = reactive({ email: '', password: '' });
            const orderLogs = ref([]);

            // Data Dummy Produk
            const products = ref([
                { id: 101, name: 'Laptop Gaming ASUS' },
                { id: 102, name: 'Mouse Logitech Silent' },
                { id: 103, name: 'Mechanical Keyboard Rexus' },
                { id: 104, name: 'Monitor LG 24 Inch' }
            ]);

            // --- FUNGSI LOGIN ---
            const handleLogin = async () => {
                if(!loginForm.email || !loginForm.password) {
                    alert('Mohon isi email dan password!');
                    return;
                }
                
                try {
                    // Request ke User Service (Port 3001)
                    const response = await axios.post('http://localhost:3001/auth/login', {
                        email: loginForm.email,
                        password: loginForm.password
                    });
                    
                    user.value = response.data.user;
                    isLoggedIn.value = true;
                } catch (error) {
                    console.error(error);
                    alert('Gagal Login! Pastikan User Service (Port 3001) sudah jalan dan CORS aktif.');
                }
            };

            // --- FUNGSI BUAT ORDER ---
            const createOrder = async (productId, productName) => {
                try {
                    // Request ke Order Service (Port 3002)
                    const response = await axios.post('http://localhost:3002/orders/create', {
                        userId: user.value.id,
                        productId: productId,
                        quantity: 1
                    });

                    // Update State (UI)
                    orderLogs.value.unshift({
                        productId: productId,
                        productName: productName,
                        quantity: 1,
                        orderId: response.data.orderId || Math.floor(Math.random() * 9999)
                    });
                    
                } catch (error) {
                    console.error(error);
                    alert('Gagal membuat order! Pastikan Order Service (Port 3002) sudah jalan.');
                }
            };

            return {
                isLoggedIn, user, loginForm, products, orderLogs,
                handleLogin, createOrder
            };
        }
    }).mount('#app');
</script>

<style>
    /* Sedikit animasi tambahan */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out; }
    .animate-pulse-once { animation: pulse 0.5s ease-in-out; }
</style>
</body>
</html>