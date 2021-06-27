// This file is part of www.nand2tetris.org
// and the book "The Elements of Computing Systems"
// by Nisan and Schocken, MIT Press.
// File name: projects/04/Fill.asm

// Runs an infinite loop that listens to the keyboard input.
// When a key is pressed (any key), the program blackens the screen,
// i.e. writes "black" in every pixel;
// the screen should remain fully black as long as the key is pressed. 
// When no key is pressed, the program clears the screen, i.e. writes
// "white" in every pixel;
// the screen should remain fully clear as long as no key is pressed.

// Put your code here.

// switch screen
(LOOP)
    @SCREEN
    D = A
    @ADDRESS
    M = D   // M = SCREENのアドレス値

    @KBD
    D = M   // キーボードが押されていれば，そのキーボードの値

    @BLACK  // キーボードが押されているので D の値が 0 より大きくなる
    D;JGT   // if (D > 0) goto BLACK

    @WHITE
    0;JMP   // goto WHITE

(BLACK)
    @COLOR  // スクリーンに表示させたい色の値
    M = -1  // -1(10) -> 1111 1111 1111 1111(2)

    @DRAW
    0;JMP

(WHITE)
    @COLOR  // スクリーンに表示させたい色の値
    M = 0

    @DRAW
    0;JMP

(DRAW)
    @COLOR
    D = M
    @ADDRESS
    A = M
    M = D   // D で入力されたアドレス値の場所のスクリーンの色を変換させる
    @ADDRESS
    M = M + 1   // 次の色が変わっていないスクリーンのアドレス値をインクリメントで指定する

    @8192   // (512 / 16) * 256 = 8K
    D = A
    @SCREEN
    D = D + A
    @ADDRESS
    D = D - M

    @DRAW
    D;JGT   // if (8192 + SCREEN - ADDRESS > 0) goto DRAW

    @LOOP
    0;JMP