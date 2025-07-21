// emojiPicker.js

// ---- Emoji group definitions ----
export const emojiGroups = {
  education: [0x1f393, 0x1f3eb],
  training_actions: [0x1f3c3, 0x1f3cb],
  learning_materials: [0x1f4d5, 0x1f4da],
  documentation: [0x1f4da, 0x1f4dd],
  professions: [0x1f9d1, 0x1f9dd],
  achievements: [0x1f396, 0x1f3c6],
  communication: [0x1f4e0, 0x1f4ef],
  office_tools: [0x1f4c0, 0x1f4cb],
  documents: [0x1f4c3, 0x1f4d6],
  finance: [0x1f4b0, 0x1f4b8],
  analytics: [0x1f4c8, 0x1f4ca],
  logistics: [0x1f690, 0x1f6a2],
  time: [0x23f0, 0x23f3],
  schedule: [0x1f4c5, 0x1f4c6],
  location: [0x1f5fa, 0x1f5ff],
  technology: [0x1f4f1, 0x1f4fa],
  tools: [0x1f527, 0x1f529],
  events: [0x1f3a4, 0x1f3a7],
};

export function generateEmojis(groupNames = []) {
  const names = groupNames.length
    ? groupNames.filter((name) => name in emojiGroups)
    : Object.keys(emojiGroups);

  const emojis = [];
  for (const name of names) {
    const [start, end] = emojiGroups[name];
    for (let code = start; code <= end; code++) {
      const ch = String.fromCodePoint(code);
      if (/\p{Emoji}/u.test(ch)) {
        emojis.push({ emoji: ch, code });
      }
    }
  }
  return emojis;
}

// Track focused input and cursor position
let activeInput = null;
let cursorPos = 0;

export default function createPicker(unique_selector, groupNames = []) {
  const container = document.querySelector(unique_selector);
  const emojis = generateEmojis(groupNames);

  container.innerHTML = emojis
    .map(
      ({ emoji, code }) =>
        `<span class="emoji" data-code="${code}">${emoji}</span>`
    )
    .join('');

  // Track focus and cursor position on all inputs/textareas
  document.addEventListener('focusin', (e) => {
    if (e.target.matches('input[type="text"], textarea')) {
      activeInput = e.target;
    }
  });

  document.addEventListener('click', (e) => {
    if (e.target.matches('input[type="text"], textarea')) {
      activeInput = e.target;
      cursorPos = e.target.selectionStart || 0;
    }
  });

  document.addEventListener('keyup', (e) => {
    if (e.target.matches('input[type="text"], textarea')) {
      cursorPos = e.target.selectionStart || 0;
    }
  });

  container.addEventListener('click', (e) => {
    if (!e.target.classList.contains('emoji')) return;

    const emoji = e.target.textContent;

    if (activeInput) {
      const start = activeInput.selectionStart || cursorPos;
      const end = activeInput.selectionEnd || cursorPos;
      const val = activeInput.value;

      activeInput.value = val.slice(0, start) + emoji + val.slice(end);

      // Restore cursor position after emoji
      const newPos = start + emoji.length;
      activeInput.setSelectionRange(newPos, newPos);
      activeInput.focus();
    }

    // Visual feedback
    container
      .querySelectorAll('.emoji.selected')
      .forEach((el) => el.classList.remove('selected'));
    e.target.classList.add('selected');
  });
}
