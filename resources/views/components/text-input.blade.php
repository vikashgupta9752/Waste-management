@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-xl shadow-sm transition-all duration-200 bg-white/50 backdrop-blur-sm']) }}>
