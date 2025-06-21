/**
 * Estimate reading time for a given text, in whole minutes.
 * Rounds up any partial minute.
 *
 * @param {string} text The text to measure.
 * @param {number} [wordsPerMinute=200] Average reading speed.
 * @returns {number} Estimated reading time in minutes.
 */
export default function reading_time(text, wordsPerMinute = 200) {
  console.log('compute reading time');
  // Count words by splitting on whitespace
  const wordCount = text.trim().split(/\s+/).filter(Boolean).length;

  // Total time in minutes (float)
  const totalMinutes = wordCount / wordsPerMinute;

  // Round up to the next whole minute
  const minutes = Math.ceil(totalMinutes);

  return minutes;
}
