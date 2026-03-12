export class Normalisator {

    static normalizePolishChars(str: string): string {
        return str.normalize('NFD').toLowerCase().replace(/[\u0300-\u036f]/g, '').replace(/[\u0142]/g, 'l');
      }
}