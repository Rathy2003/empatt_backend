<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* Keyframe animation for fade-in effect */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 30px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        /* Animation utility class */
        .animate-fadeInUp {
            opacity: 0; /* Set initial state for animation */
            animation: fadeInUp 0.8s ease-out forwards;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="text-center p-8">
            <div class="mx-auto w-64 h-48 mb-8 animate-fadeInUp" style="animation-delay: 0.1s;">
                <img src="{{ asset('404.gif') }}" alt="Lost person GIF" class="w-full h-full object-contain rounded-lg">
            </div>
            <h2 class="text-3xl font-semibold text-gray-800 mt-4 animate-fadeInUp" style="animation-delay: 0.5s;">Page Not Found</h2>
            <p class="text-gray-600 mt-2 max-w-md mx-auto animate-fadeInUp" style="animation-delay: 0.7s;">
                Oops! Looks like you're lost. The page you are looking for does not exist.
            </p>
            
            <div class="mt-8 animate-fadeInUp" style="animation-delay: 0.9s;">
                <a href="/" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-all duration-300 hover:shadow-xl hover:scale-105">
                    Go to Homepage
                </a>
            </div>
        </div>
    </div>

</body>
</html>
