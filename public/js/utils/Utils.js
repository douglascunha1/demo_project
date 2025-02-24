/**
 * Class containing utility functions for string and date manipulation
 */
class Utils {
    /**
     * Converts the first character of each word to uppercase
     * @param {string} str - Input string
     * @returns {string}
     */
    static toTitleCase(str) {
        return str.toLowerCase().replace(/(?:^|\s)\w/g, function(match) {
            return match.toUpperCase();
        });
    }

    /**
     * Converts string to uppercase
     * @param {string} str - Input string
     * @returns {string}
     */
    static toUpperCase(str) {
        return str.toUpperCase();
    }

    /**
     * Converts string to lowercase
     * @param {string} str - Input string
     * @returns {string}
     */
    static toLowerCase(str) {
        return str.toLowerCase();
    }

    /**
     * Converts the first character to uppercase
     * @param {string} str - Input string
     * @returns {string}
     */
    static capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }

    /**
     * Removes all spaces from string
     * @param {string} str - Input string
     * @returns {string}
     */
    static removeSpaces(str) {
        return str.replace(/\s/g, '');
    }

    /**
     * Removes special characters from string
     * @param {string} str - Input string
     * @returns {string}
     */
    static removeSpecialChars(str) {
        return str.replace(/[^a-zA-Z0-9-]/g, '');
    }

    /**
     * Formats a date string to specified format using jQuery
     * @param {string} date - Date string
     * @param {string} format - Desired format
     * @returns {string}
     */
    static formatDate(date, format = 'YYYY-MM-DD') {
        return $.datepicker.formatDate(format, new Date(date));
    }

    /**
     * Converts date to Brazilian format (dd/mm/yyyy)
     * @param {string} date - Date string
     * @returns {string}
     */
    static toBrazilianDate(date) {
        return $.datepicker.formatDate('dd/mm/yy', new Date(date));
    }

    /**
     * Checks if string contains only numbers
     * @param {string} str - Input string
     * @returns {boolean}
     */
    static isNumeric(str) {
        return /^\d+$/.test(str);
    }

    /**
     * Masks a string based on pattern
     * @param {string} str - Input string
     * @param {string} pattern - Mask pattern
     * @returns {string}
     */
    static mask(str, pattern) {
        let i = 0;
        const v = str.toString();
        return pattern.replace(/#/g, () => v[i++] || '');
    }

    /**
     * Slugifies a string (converts to URL-friendly format)
     * @param {string} str - Input string
     * @returns {string}
     */
    static slugify(str) {
        str = str.toLowerCase();
        str = str.replace(/[^a-z0-9-]/g, '-');
        str = str.replace(/-+/g, '-');
        return str.replace(/^-|-$/g, '');
    }

    /**
     * Extracts only numbers from string
     * @param {string} str - Input string
     * @returns {string}
     */
    static onlyNumbers(str) {
        return str.replace(/[^\d]/g, '');
    }

    /**
     * Formats CPF number
     * @param {string} cpf - CPF number
     * @returns {string}
     */
    static formatCPF(cpf) {
        cpf = this.onlyNumbers(cpf);
        return this.mask(cpf, '###.###.###-##');
    }

    /**
     * Formats CNPJ number
     * @param {string} cnpj - CNPJ number
     * @returns {string}
     */
    static formatCNPJ(cnpj) {
        cnpj = this.onlyNumbers(cnpj);
        return this.mask(cnpj, '##.###.###/####-##');
    }

    /**
     * Formats currency value to Brazilian Real (R$)
     * @param {number} value - Numeric value
     * @returns {string}
     */
    static formatCurrency(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    }

    /**
     * Validates if a string is a valid email
     * @param {string} email - Email address
     * @returns {boolean}
     */
    static isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    /**
     * Truncates text to a specified length
     * @param {string} str - Input string
     * @param {number} length - Maximum length
     * @param {string} suffix - Suffix to add if truncated
     * @returns {string}
     */
    static truncate(str, length, suffix = '...') {
        if (str.length <= length) return str;
        return str.substring(0, length).trim() + suffix;
    }

    /**
     * Formats phone number
     * @param {string} phone - Phone number
     * @returns {string}
     */
    static formatPhone(phone) {
        phone = this.onlyNumbers(phone);
        if (phone.length === 11) {
            return this.mask(phone, '(##) #####-####');
        }
        return this.mask(phone, '(##) ####-####');
    }
}