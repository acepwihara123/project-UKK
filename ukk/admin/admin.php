            <main class="flex-1 p-6 content-main content-scrollable">
                <!-- Header dengan filter -->
                <div class="mb-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Selamat Datang, Admin!</h2>
                            <p class="text-gray-600">Berikut adalah ringkasan data statistik.</p>
                        </div>
                        
                        <div class="mt-4 md:mt-0">
                            <div class="flex items-center space-x-2">
                                <div class="relative">
                                    <i class="fas fa-calendar-alt absolute left-3 top-3 text-gray-400"></i>
                                    <select class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                        <option>Hari Ini</option>
                                        <option>Minggu Ini</option>
                                        <option>Bulan Ini</option>
                                        <option>3 Bulan Terakhir</option>
                                    </select>
                                </div>
                                <button class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition">
                                    <i class="fas fa-download mr-2"></i>
                                    Ekspor
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cards Statistik -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Total Pengguna</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">0</h3>
                            </div>
                            <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-users text-primary text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card 2 -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-success">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Total Pendapatan</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp 0</h3>
                            </div>
                            <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                                <i class="fas fa-wallet text-success text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card 3 -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-warning">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Total Transaksi</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">0</h3>
                            </div>
                            <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-warning text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card 4 -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-info">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Rata-rata Waktu</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">0s</h3>
                            </div>
                            <div class="h-12 w-12 rounded-lg bg-cyan-100 flex items-center justify-center">
                                <i class="fas fa-clock text-info text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grafik dan Tabel -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Grafik -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-800">Grafik</h3>
                        </div>
                        <div class="h-72 bg-gray-100 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-chart-bar text-4xl text-gray-300 mb-2"></i>
                                <p class="text-gray-400">Tidak ada data untuk ditampilkan</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tabel -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-800">Data Terbaru</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 text-gray-600 font-medium">Kolom 1</th>
                                        <th class="text-left py-3 text-gray-600 font-medium">Kolom 2</th>
                                        <th class="text-left py-3 text-gray-600 font-medium">Kolom 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="py-8 text-center text-gray-500">
                                            <i class="fas fa-database text-3xl mb-2 block"></i>
                                            <p>Tidak ada data untuk ditampilkan</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <footer class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-500 text-sm">
                    <p>© 2023 Dashboard Statistik</p>
                </footer>
            </main>