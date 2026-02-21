function initDocs() {
    // Highlight all code blocks
    if (typeof hljs === 'undefined') return;

    document.querySelectorAll('.docs-body pre code').forEach((block) => {
        if (block.dataset.highlighted) return;

        hljs.highlightElement(block);

        // Add copy button
        const button = document.createElement('button');
        button.textContent = 'Copy';
        button.className = 'copy-btn absolute top-2 left-2 text-xs px-2 py-1 rounded opacity-0 transition';
        button.addEventListener('click', () => {
            navigator.clipboard.writeText(block.innerText).then(() => {
                button.textContent = 'Copied!';
                setTimeout(() => {
                    button.textContent = 'Copy';
                }, 2000);
            });
        });

        const pre = block.parentElement;
        pre.style.position = 'relative';
        pre.addEventListener('mouseenter', () => {
            button.style.opacity = '1';
        });
        pre.addEventListener('mouseleave', () => {
            button.style.opacity = '0';
        });
        pre.appendChild(button);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('.docs-body a[href^="#"]').forEach((link) => {
        link.addEventListener('click', (e) => {
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

// Re-init on Livewire navigation
document.addEventListener('livewire:navigated', () => {
    if (typeof hljs !== 'undefined') {
        initDocs();
    }
});
