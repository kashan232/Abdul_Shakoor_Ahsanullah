/** 
 * Urdu Visual Keyboard Logic
 * Version: 1.0.0
 * Purpose: Local, on-screen keyboard for Urdu input.
 */

const UrduKeyboard = (function() {
    let activeInput = null;
    let shiftMode = false;
    let kbWrapper = null;

    // Urdu layout character maps (Phonetic-ish)
    const layouts = {
        normal: [
            ["ض", "ص", "ث", "ق", "ف", "غ", "ع", "ه", "خ", "ح", "ج", "چ"],
            ["ش", "س", "ی", "ب", "ل", "ا", "ت", "ن", "م", "ک", "گ"],
            ["Shift", "ظ", "ط", "ز", "ر", "ذ", "د", "و", "پ", "ٹ", "ڈ", "ڑ"],
            ["ء", "آ", "ؤ", "ئ", "ے", "Space", "Back", "Close"]
        ],
        shift: [
            ["!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "+"],
            ["ژ", "ښ", "ى", "ب", "ل", "آ", "ة", "ں", "م", "ك", "گ"],
            ["Shift", "؟", "!", "ٍ", "ِ", "ُ", "ً", "َ", "ّ", "ۂ", "ہ", "ؤ"],
            ["ء", "ئ", "«", "»", "ۓ", "Space", "Back", "Close"]
        ]
    };

    function init() {
        // Create keyboard HTML structure if not exists
        if (document.querySelector('.urdu-kb-wrapper')) return;

        kbWrapper = document.createElement('div');
        kbWrapper.className = 'urdu-kb-wrapper';
        kbWrapper.innerHTML = `
            <div class="urdu-kb-container">
                <div class="urdu-kb-header">
                    <div class="urdu-kb-title">
                        <i class="fas fa-keyboard"></i> Urdu Keyboard
                    </div>
                    <div class="urdu-kb-close">✕ Close</div>
                </div>
                <div class="urdu-kb-rows"></div>
            </div>
        `;
        document.body.appendChild(kbWrapper);

        // Bind events
        renderKeyboard('normal');
        bindGlobalEvents();
    }

    function renderKeyboard(mode) {
        const rowsContainer = kbWrapper.querySelector('.urdu-kb-rows');
        rowsContainer.innerHTML = '';
        
        layouts[mode].forEach(row => {
            const rowDiv = document.createElement('div');
            rowDiv.className = 'urdu-kb-row';
            
            row.forEach(key => {
                const keyBtn = document.createElement('div');
                keyBtn.className = 'urdu-kb-key';
                keyBtn.innerText = key;

                if (key === 'Space') keyBtn.classList.add('space');
                if (key === 'Shift') {
                    keyBtn.classList.add('shift', 'wide');
                    if (shiftMode) keyBtn.classList.add('active');
                }
                if (key === 'Back') keyBtn.classList.add('backspace', 'wide');
                if (key === 'Close') keyBtn.classList.add('close-btn', 'wide');

                keyBtn.addEventListener('mousedown', (e) => {
                    e.preventDefault(); // Prevent input blurring
                    handleKeyPress(key);
                });
                
                rowDiv.appendChild(keyBtn);
            });
            rowsContainer.appendChild(rowDiv);
        });
    }

    function handleKeyPress(key) {
        if (!activeInput) return;

        const start = activeInput.selectionStart;
        const end = activeInput.selectionEnd;
        const val = activeInput.value;

        if (key === 'Shift') {
            shiftMode = !shiftMode;
            renderKeyboard(shiftMode ? 'shift' : 'normal');
        } else if (key === 'Back') {
            if (start === end) {
                activeInput.value = val.substring(0, start - 1) + val.substring(end);
                activeInput.setSelectionRange(start - 1, start - 1);
            } else {
                activeInput.value = val.substring(0, start) + val.substring(end);
                activeInput.setSelectionRange(start, start);
            }
        } else if (key === 'Space') {
            insertAtCursor(' ');
        } else if (key === 'Close') {
            hide();
        } else {
            insertAtCursor(key);
            if (shiftMode) {
                shiftMode = false;
                renderKeyboard('normal');
            }
        }
        
        activeInput.focus();
        activeInput.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function insertAtCursor(text) {
        const start = activeInput.selectionStart;
        const end = activeInput.selectionEnd;
        const val = activeInput.value;
        activeInput.value = val.substring(0, start) + text + val.substring(end);
        activeInput.setSelectionRange(start + text.length, start + text.length);
    }

    function bindGlobalEvents() {
        document.addEventListener('focusin', (e) => {
            if (e.target.classList.contains('urdu-input')) {
                activeInput = e.target;
                show();
            }
        });

        document.addEventListener('click', (e) => {
            if (!kbWrapper.contains(e.target) && !e.target.classList.contains('urdu-input')) {
                hide();
            }
        });

        // Physical Keyboard Support
        document.addEventListener('keydown', (e) => {
            if (activeInput && e.key === 'Shift') {
                shiftMode = true;
                renderKeyboard('shift');
            }
        });
        document.addEventListener('keyup', (e) => {
            if (activeInput && e.key === 'Shift') {
                shiftMode = false;
                renderKeyboard('normal');
            }
        });

        kbWrapper.querySelector('.urdu-kb-close').addEventListener('click', hide);
    }

    function show() {
        kbWrapper.classList.add('active');
    }

    function hide() {
        kbWrapper.classList.remove('active');
        activeInput = null;
    }

    return { init };
})();

// Initialize on page load
document.addEventListener('DOMContentLoaded', UrduKeyboard.init);
