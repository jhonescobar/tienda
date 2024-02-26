class MyMatrix {
    constructor(matrix) {
        this.matrix = matrix;
    }
  
    // Método que devuelve un entero que define la dimensión del arreglo o matriz en su mayor profundidad
    dimension() {
        let profundidad = 1;
        let recorridos = this.matrix;
        for (let i = 0; i < recorridos.length; i++) {
          if(Array.isArray(recorridos[i])) {
            profundidad++;
            recorridos = recorridos[i];
          }
        }
        
        return profundidad;
    }
  
    // Método para verificar si la matriz es uniforme en todas sus dimensiones
    straight() {
        if (!Array.isArray(this.matrix)) {
            return false;
        }
    
        // Obtener la longitud del primer sub-array (si existe)
        const ref = this.matrix[0].length;
    
        // Verificar si todos los sub-arrays (o elementos) tienen la misma longitud
        return this.matrix.every(elem => {
            // Si el elemento es un array, llamar recursivamente a validarMatriz
            if (Array.isArray(elem)) {
                return (new MyMatrix(elem)).straight() && elem.length === ref;
            } else {
                // Si el elemento no es un array, solo verificar su longitud
                return elem.length === ref;
            }
        });
    }
  
    // Método para calcular la suma de todos los elementos en la matriz
    compute() {
        let sum = 0;
        this.flatten(this.matrix).forEach(num => sum += num);
        return sum;
    }

    flatten(arr) {
        return arr.reduce((acc, val) => Array.isArray(val) ? acc.concat(this.flatten(val)) : acc.concat(val), []);
    }
}

// Casos
const a = [1, 2];
const b = [[1, 2], [2, 4]];
const c = [[1, 2], [2, 4], [2, 4]];
const d = [[[3, 4], [6, 5]]];
const e = [[[1, 2, 3]], [[5, 6, 7], [5, 4, 3]], [[3, 5, 6], [4, 8, 3], [2, 3]]];
const f = [[[1, 2, 3], [2, 3, 4]], [[5, 6, 7], [5, 4, 3]], [[3, 5, 6], [4, 8, 3]]];
const g = [[[[1, 2, 3], [2, 3, 4]], [[5, 6, 7], [5, 4, 3]], [[3, 5, 6], [4, 8, 3]]]];

console.log(new MyMatrix(a).dimension());
console.log(new MyMatrix(b).dimension());
console.log(new MyMatrix(c).dimension());
console.log(new MyMatrix(d).dimension());
console.log(new MyMatrix(e).dimension());
console.log(new MyMatrix(f).dimension());
console.log(new MyMatrix(g).dimension());

console.log(new MyMatrix(a).straight());
console.log(new MyMatrix(b).straight());
console.log(new MyMatrix(c).straight());
console.log(new MyMatrix(d).straight());
console.log(new MyMatrix(e).straight());
console.log(new MyMatrix(f).straight());
console.log(new MyMatrix(g).straight());

console.log(new MyMatrix(a).compute());
console.log(new MyMatrix(b).compute());
console.log(new MyMatrix(c).compute());
console.log(new MyMatrix(d).compute());
console.log(new MyMatrix(e).compute());
console.log(new MyMatrix(f).compute());
console.log(new MyMatrix(g).compute());