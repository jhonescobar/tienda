class MyMatrix {
    constructor(string) {
        this.string = string;
    }
  
    operation() {
        // Expresión regular para verificar si el string contiene una operación aritmética válida
        const regex = /[+\-*/]\s*[0-9]+(?:\s*[+\-*/]\s*[0-9]+)*$/;
        return regex.test(this.string);
    }

    compute() {
        if (!this.operation()) {
            return false;
        }
        
        try {
            // Evalua el contenido del string
            return eval(this.string);
        } catch (error) {
            return false;
        }
    }
}

// Casos
const a = "Hello world";
const b = "2 + 10 / 2 - 20";
const c = "(2 + 10) / 2 - 20";
const d = "(2 + 10 / 2 - 20";

console.log(new MyMatrix(a).operation());
console.log(new MyMatrix(b).operation());
console.log(new MyMatrix(c).operation());
console.log(new MyMatrix(d).operation());

console.log(new MyMatrix(a).compute());
console.log(new MyMatrix(b).compute());
console.log(new MyMatrix(c).compute());
console.log(new MyMatrix(d).compute());