//
//  ViewController.swift
//  STAX
//
//  Created by Beltrax on 10/16/17.
//  Copyright © 2017 TEAM STAX. All rights reserved.
//

import UIKit

class ViewController: UIViewController {
   
    @IBOutlet weak var uiText: UILabel!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    @IBAction func changeText(_ sender: AnyObject) {
        var phraseList: [String] = ["Eggs", "Milk","Hello world", "Colorado","Barbara Stereisand"]
        let randomNum:UInt32 = arc4random_uniform(5) // range is 0 to 4
        let random:Int = Int(randomNum)
        uiText.text = phraseList[random]
    }
    
    @IBAction func eraseText(_ sender: AnyObject) {
        uiText.text = "-"
    }
    
}

