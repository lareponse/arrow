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

/**
 * Build an array of { emoji, code } objects for the requested groups.
 * @param {string[]} groupNames - Keys from emojiGroups; defaults to all groups.
 */
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

// ---- Picker injection & selection handler ----
/**
 * Renders an emoji picker into `container` and wires up selection.
 * @param {HTMLElement} container  – where to render <span class="emoji">…
 * @param {string[]} groupNames   – which emojiGroups to include (default: all)
 */
export default function createPicker(unique_selector, groupNames = []) {
  const container = document.querySelector(unique_selector);
  const emojis = generateEmojis(groupNames);
  container.innerHTML = emojis
    .map(
      ({ emoji, code }) =>
        `<span class="emoji" data-code="${code}">${emoji}</span>`
    )
    .join('');

  container.addEventListener('click', (e) => {
    if (!e.target.classList.contains('emoji')) return;

    // clear old selection
    container
      .querySelectorAll('.emoji.selected')
      .forEach((el) => el.classList.remove('selected'));

    // mark new
    e.target.classList.add('selected');
    const code = +e.target.dataset.code;
    const hex = `0x${code.toString(16).toUpperCase()}`;

    // update any display targets
    const setText = (id, text) => {
      const el = document.getElementById(id);
      if (el) el.textContent = text;
    };
    setText('selected', e.target.textContent);
    setText('unicode', code);
    setText('hex', hex);

    // optional callback
    if (typeof window.onEmojiSelect === 'function') {
      window.onEmojiSelect({
        emoji: e.target.textContent,
        unicode: code,
        hex,
      });
    }
  });
}
