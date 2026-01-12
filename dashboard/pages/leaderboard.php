<!-- Main Content -->

<style>



</style>
<div class="content">



</div>

<main class="pt-16 lg:pl-64 min-h-screen">
    <div class="p-6">
        <!-- Page Header -->
        <div class="glass rounded-2xl p-6 mb-6 card-hover">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2 flex items-center">
                        <svg class="w-8 h-8 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Eco Warriors Leaderboard
                    </h1>
                    <p class="text-gray-300">See how you rank among top recyclers worldwide</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-r from-green-500/20 to-blue-500/20 rounded-lg px-4 py-2">
                        <p class="text-sm text-gray-300">Your Rank</p>
                        <p class="text-2xl font-bold text-white " id="yourRank"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="glass rounded-2xl p-6 mb-6 card-hover">
            <div class="flex flex-wrap gap-2">
                <button class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 transform hover:scale-105">
                    Global
                </button>
                <button class="bg-white/10 text-gray-300 hover:bg-white/20 px-4 py-2 rounded-lg font-medium transition-all duration-300">
                    Friends
                </button>
                <button class="bg-white/10 text-gray-300 hover:bg-white/20 px-4 py-2 rounded-lg font-medium transition-all duration-300">
                    Local
                </button>
                <button class="bg-white/10 text-gray-300 hover:bg-white/20 px-4 py-2 rounded-lg font-medium transition-all duration-300">
                    This Month
                </button>
            </div>
        </div>

        <!-- Top 3 Podium -->
        <div class="glass rounded-2xl p-6 mb-6 card-hover">
            <h2 class="text-xl font-semibold text-white mb-6 text-center">Top Eco Warriors</h2>
            <div class="flex justify-center items-end space-x-4">
                <!-- 2nd Place -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-gray-400 to-gray-600 rounded-full flex items-center justify-center mb-3 mx-auto">
                        <svg width="64px" height="64px" class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M11.1459 7.02251C11.5259 6.34084 11.7159 6 12 6C12.2841 6 12.4741 6.34084 12.8541 7.02251L12.9524 7.19887C13.0603 7.39258 13.1143 7.48944 13.1985 7.55334C13.2827 7.61725 13.3875 7.64097 13.5972 7.68841L13.7881 7.73161C14.526 7.89857 14.895 7.98205 14.9828 8.26432C15.0706 8.54659 14.819 8.84072 14.316 9.42898L14.1858 9.58117C14.0429 9.74833 13.9714 9.83191 13.9392 9.93531C13.9071 10.0387 13.9179 10.1502 13.9395 10.3733L13.9592 10.5763C14.0352 11.3612 14.0733 11.7536 13.8435 11.9281C13.6136 12.1025 13.2682 11.9435 12.5773 11.6254L12.3986 11.5431C12.2022 11.4527 12.1041 11.4075 12 11.4075C11.8959 11.4075 11.7978 11.4527 11.6014 11.5431L11.4227 11.6254C10.7318 11.9435 10.3864 12.1025 10.1565 11.9281C9.92674 11.7536 9.96476 11.3612 10.0408 10.5763L10.0605 10.3733C10.0821 10.1502 10.0929 10.0387 10.0608 9.93531C10.0286 9.83191 9.95713 9.74833 9.81418 9.58117L9.68403 9.42898C9.18097 8.84072 8.92945 8.54659 9.01723 8.26432C9.10501 7.98205 9.47396 7.89857 10.2119 7.73161L10.4028 7.68841C10.6125 7.64097 10.7173 7.61725 10.8015 7.55334C10.8857 7.48944 10.9397 7.39258 11.0476 7.19887L11.1459 7.02251Z" stroke="#939393" stroke-width="1.5"></path>
                                <path d="M7.35111 15L6.71424 17.323C6.0859 19.6148 5.77173 20.7607 6.19097 21.3881C6.3379 21.6079 6.535 21.7844 6.76372 21.9008C7.41635 22.2331 8.42401 21.7081 10.4393 20.658C11.1099 20.3086 11.4452 20.1339 11.8014 20.0959C11.9335 20.0818 12.0665 20.0818 12.1986 20.0959C12.5548 20.1339 12.8901 20.3086 13.5607 20.658C15.576 21.7081 16.5837 22.2331 17.2363 21.9008C17.465 21.7844 17.6621 21.6079 17.809 21.3881C18.2283 20.7607 17.9141 19.6148 17.2858 17.323L16.6489 15" stroke="#939393" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M5.5 6.39691C5.17745 7.20159 5 8.08007 5 9C5 12.866 8.13401 16 12 16C15.866 16 19 12.866 19 9C19 5.13401 15.866 2 12 2C11.0801 2 10.2016 2.17745 9.39691 2.5" stroke="#939393" stroke-width="1.5" stroke-linecap="round"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="bg-gray-500/20 rounded-lg p-3 h-24 flex flex-col justify-center">
                        <p class="text-white font-semibold" id="rank2"></p>
                        <p class="text-gray-300 text-sm" id="rank2pts"></p>
                        <p class="text-gray-400 text-xs" id="rank2items"></p>
                    </div>
                </div>

                <!-- 1st Place -->
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center mb-3 mx-auto ring-4 ring-yellow-400/30">
                        <svg width="64px" height="64px" class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M11.1459 7.02251C11.5259 6.34084 11.7159 6 12 6C12.2841 6 12.4741 6.34084 12.8541 7.02251L12.9524 7.19887C13.0603 7.39258 13.1143 7.48944 13.1985 7.55334C13.2827 7.61725 13.3875 7.64097 13.5972 7.68841L13.7881 7.73161C14.526 7.89857 14.895 7.98205 14.9828 8.26432C15.0706 8.54659 14.819 8.84072 14.316 9.42898L14.1858 9.58117C14.0429 9.74833 13.9714 9.83191 13.9392 9.93531C13.9071 10.0387 13.9179 10.1502 13.9395 10.3733L13.9592 10.5763C14.0352 11.3612 14.0733 11.7536 13.8435 11.9281C13.6136 12.1025 13.2682 11.9435 12.5773 11.6254L12.3986 11.5431C12.2022 11.4527 12.1041 11.4075 12 11.4075C11.8959 11.4075 11.7978 11.4527 11.6014 11.5431L11.4227 11.6254C10.7318 11.9435 10.3864 12.1025 10.1565 11.9281C9.92674 11.7536 9.96476 11.3612 10.0408 10.5763L10.0605 10.3733C10.0821 10.1502 10.0929 10.0387 10.0608 9.93531C10.0286 9.83191 9.95713 9.74833 9.81418 9.58117L9.68403 9.42898C9.18097 8.84072 8.92945 8.54659 9.01723 8.26432C9.10501 7.98205 9.47396 7.89857 10.2119 7.73161L10.4028 7.68841C10.6125 7.64097 10.7173 7.61725 10.8015 7.55334C10.8857 7.48944 10.9397 7.39258 11.0476 7.19887L11.1459 7.02251Z" stroke="#bb8044" stroke-width="1.5"></path>
                                <path d="M7.35111 15L6.71424 17.323C6.0859 19.6148 5.77173 20.7607 6.19097 21.3881C6.3379 21.6079 6.535 21.7844 6.76372 21.9008C7.41635 22.2331 8.42401 21.7081 10.4393 20.658C11.1099 20.3086 11.4452 20.1339 11.8014 20.0959C11.9335 20.0818 12.0665 20.0818 12.1986 20.0959C12.5548 20.1339 12.8901 20.3086 13.5607 20.658C15.576 21.7081 16.5837 22.2331 17.2363 21.9008C17.465 21.7844 17.6621 21.6079 17.809 21.3881C18.2283 20.7607 17.9141 19.6148 17.2858 17.323L16.6489 15" stroke="#bb8044" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M5.5 6.39691C5.17745 7.20159 5 8.08007 5 9C5 12.866 8.13401 16 12 16C15.866 16 19 12.866 19 9C19 5.13401 15.866 2 12 2C11.0801 2 10.2016 2.17745 9.39691 2.5" stroke="#bb8044" stroke-width="1.5" stroke-linecap="round"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="bg-yellow-500/20 rounded-lg p-3 h-32 flex flex-col justify-center">
                        <p class="text-white font-bold text-lg" id="rank1"></p>
                        <p class="text-yellow-400 text-sm font-semibold" id="rank1pts"></p>
                        <p class="text-gray-300 text-xs" id="rank1items"></p>
                    </div>
                </div>

                <!-- 3rd Place -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-amber-600 to-amber-800 rounded-full flex items-center justify-center mb-3 mx-auto">
                        <svg width="64px" class="w-10 h-10 text-white" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M11.1459 7.02251C11.5259 6.34084 11.7159 6 12 6C12.2841 6 12.4741 6.34084 12.8541 7.02251L12.9524 7.19887C13.0603 7.39258 13.1143 7.48944 13.1985 7.55334C13.2827 7.61725 13.3875 7.64097 13.5972 7.68841L13.7881 7.73161C14.526 7.89857 14.895 7.98205 14.9828 8.26432C15.0706 8.54659 14.819 8.84072 14.316 9.42898L14.1858 9.58117C14.0429 9.74833 13.9714 9.83191 13.9392 9.93531C13.9071 10.0387 13.9179 10.1502 13.9395 10.3733L13.9592 10.5763C14.0352 11.3612 14.0733 11.7536 13.8435 11.9281C13.6136 12.1025 13.2682 11.9435 12.5773 11.6254L12.3986 11.5431C12.2022 11.4527 12.1041 11.4075 12 11.4075C11.8959 11.4075 11.7978 11.4527 11.6014 11.5431L11.4227 11.6254C10.7318 11.9435 10.3864 12.1025 10.1565 11.9281C9.92674 11.7536 9.96476 11.3612 10.0408 10.5763L10.0605 10.3733C10.0821 10.1502 10.0929 10.0387 10.0608 9.93531C10.0286 9.83191 9.95713 9.74833 9.81418 9.58117L9.68403 9.42898C9.18097 8.84072 8.92945 8.54659 9.01723 8.26432C9.10501 7.98205 9.47396 7.89857 10.2119 7.73161L10.4028 7.68841C10.6125 7.64097 10.7173 7.61725 10.8015 7.55334C10.8857 7.48944 10.9397 7.39258 11.0476 7.19887L11.1459 7.02251Z" stroke="#7b4444" stroke-width="1.5"></path>
                                <path d="M7.35111 15L6.71424 17.323C6.0859 19.6148 5.77173 20.7607 6.19097 21.3881C6.3379 21.6079 6.535 21.7844 6.76372 21.9008C7.41635 22.2331 8.42401 21.7081 10.4393 20.658C11.1099 20.3086 11.4452 20.1339 11.8014 20.0959C11.9335 20.0818 12.0665 20.0818 12.1986 20.0959C12.5548 20.1339 12.8901 20.3086 13.5607 20.658C15.576 21.7081 16.5837 22.2331 17.2363 21.9008C17.465 21.7844 17.6621 21.6079 17.809 21.3881C18.2283 20.7607 17.9141 19.6148 17.2858 17.323L16.6489 15" stroke="#7b4444" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M5.5 6.39691C5.17745 7.20159 5 8.08007 5 9C5 12.866 8.13401 16 12 16C15.866 16 19 12.866 19 9C19 5.13401 15.866 2 12 2C11.0801 2 10.2016 2.17745 9.39691 2.5" stroke="#7b4444" stroke-width="1.5" stroke-linecap="round"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="bg-amber-700/20 rounded-lg p-3 h-24 flex flex-col justify-center">
                        <p class="text-white font-semibold" id="rank3"></p>
                        <p class="text-gray-300 text-sm" id="rank3pts"></p>
                        <p class="text-gray-400 text-xs" id="rank3items"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaderboard Table -->
        <div class="glass rounded-2xl p-6 card-hover">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white">Rankings</h3>
                <div class="flex items-center space-x-2 text-sm text-gray-300">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Updated 2 min ago</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="text-left py-4 px-6 text-gray-300 font-medium">Rank</th>
                            <th class="text-left py-4 px-6 text-gray-300 font-medium">User</th>
                            <th class="text-left py-4 px-6 text-gray-300 font-medium">Points</th>
                            <th class="text-left py-4 px-6 text-gray-300 font-medium">Recycled Items</th>
                            <th class="text-left py-4 px-6 text-gray-300 font-medium">Streak</th>
                        </tr>
                    </thead>
                    <tbody id="leaderboardTable">



                    </tbody>
                </table>
            </div>

            <!-- Load More Button -->

        </div>

        <!-- Achievement Banner -->
        <div class="glass rounded-2xl p-6 mt-6 card-hover">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mr-4">
                        <svg width="64px" class="w-10 h-10 text-white" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M9.15316 5.40838C10.4198 3.13613 11.0531 2 12 2C12.9469 2 13.5802 3.13612 14.8468 5.40837L15.1745 5.99623C15.5345 6.64193 15.7144 6.96479 15.9951 7.17781C16.2757 7.39083 16.6251 7.4699 17.3241 7.62805L17.9605 7.77203C20.4201 8.32856 21.65 8.60682 21.9426 9.54773C22.2352 10.4886 21.3968 11.4691 19.7199 13.4299L19.2861 13.9372C18.8096 14.4944 18.5713 14.773 18.4641 15.1177C18.357 15.4624 18.393 15.8341 18.465 16.5776L18.5306 17.2544C18.7841 19.8706 18.9109 21.1787 18.1449 21.7602C17.3788 22.3417 16.2273 21.8115 13.9243 20.7512L13.3285 20.4768C12.6741 20.1755 12.3469 20.0248 12 20.0248C11.6531 20.0248 11.3259 20.1755 10.6715 20.4768L10.0757 20.7512C7.77268 21.8115 6.62118 22.3417 5.85515 21.7602C5.08912 21.1787 5.21588 19.8706 5.4694 17.2544L5.53498 16.5776C5.60703 15.8341 5.64305 15.4624 5.53586 15.1177C5.42868 14.773 5.19043 14.4944 4.71392 13.9372L4.2801 13.4299C2.60325 11.4691 1.76482 10.4886 2.05742 9.54773C2.35002 8.60682 3.57986 8.32856 6.03954 7.77203L6.67589 7.62805C7.37485 7.4699 7.72433 7.39083 8.00494 7.17781C8.28555 6.96479 8.46553 6.64194 8.82547 5.99623L9.15316 5.40838Z" fill="#e7a358"></path>
                            </g>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-lg">Keep Climbing!</h3>
                        <p class="text-gray-300">You need <span class="text-green-400 font-semibold" id="achievementPoints"></span> points to reach the top 1!</p>
                    </div>
                </div>
                <button class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                    Recycle Now
                </button>
            </div>
        </div>
    </div>
</main>